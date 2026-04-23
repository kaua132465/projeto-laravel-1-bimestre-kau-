from pathlib import Path


PAGE_WIDTH = 842
PAGE_HEIGHT = 595


def escape_text(value: str) -> str:
    return value.replace("\\", "\\\\").replace("(", "\\(").replace(")", "\\)")


def text_width(label: str, size: int) -> float:
    return len(label) * size * 0.46


class PdfCanvas:
    def __init__(self) -> None:
        self.ops: list[str] = []

    def add(self, op: str) -> None:
        self.ops.append(op)

    def set_stroke(self, r: float, g: float, b: float) -> None:
        self.add(f"{r:.3f} {g:.3f} {b:.3f} RG")

    def set_fill(self, r: float, g: float, b: float) -> None:
        self.add(f"{r:.3f} {g:.3f} {b:.3f} rg")

    def line_width(self, width: float) -> None:
        self.add(f"{width:.2f} w")

    def rect(self, x: float, y: float, w: float, h: float, fill: bool = False) -> None:
        self.add(f"{x:.2f} {y:.2f} {w:.2f} {h:.2f} re {'B' if fill else 'S'}")

    def line(self, x1: float, y1: float, x2: float, y2: float) -> None:
        self.add(f"{x1:.2f} {y1:.2f} m {x2:.2f} {y2:.2f} l S")

    def dashed_line(self, x1: float, y1: float, x2: float, y2: float) -> None:
        self.add("[5 4] 0 d")
        self.line(x1, y1, x2, y2)
        self.add("[] 0 d")

    def polygon(self, points: list[tuple[float, float]], fill: bool = False) -> None:
        if not points:
            return
        parts = [f"{points[0][0]:.2f} {points[0][1]:.2f} m"]
        parts.extend(f"{x:.2f} {y:.2f} l" for x, y in points[1:])
        parts.append("h")
        parts.append("B" if fill else "S")
        self.add(" ".join(parts))

    def ellipse(self, cx: float, cy: float, rx: float, ry: float, fill: bool = False) -> None:
        k = 0.552284749831
        ox = rx * k
        oy = ry * k
        self.add(
            (
                f"{cx + rx:.2f} {cy:.2f} m "
                f"{cx + rx:.2f} {cy + oy:.2f} {cx + ox:.2f} {cy + ry:.2f} {cx:.2f} {cy + ry:.2f} c "
                f"{cx - ox:.2f} {cy + ry:.2f} {cx - rx:.2f} {cy + oy:.2f} {cx - rx:.2f} {cy:.2f} c "
                f"{cx - rx:.2f} {cy - oy:.2f} {cx - ox:.2f} {cy - ry:.2f} {cx:.2f} {cy - ry:.2f} c "
                f"{cx + ox:.2f} {cy - ry:.2f} {cx + rx:.2f} {cy - oy:.2f} {cx + rx:.2f} {cy:.2f} c "
                f"{'B' if fill else 'S'}"
            )
        )

    def text(self, x: float, y: float, value: str, size: int = 12, font: str = "F1") -> None:
        safe = escape_text(value)
        self.add(f"BT /{font} {size} Tf 1 0 0 1 {x:.2f} {y:.2f} Tm ({safe}) Tj ET")

    def centered_text(self, cx: float, cy: float, value: str, size: int = 12, leading: int | None = None) -> None:
        lines = value.split("\n")
        lead = leading or int(size * 1.3)
        total = lead * (len(lines) - 1)
        start_y = cy + total / 2 - size / 2
        for index, line in enumerate(lines):
            x = cx - text_width(line, size) / 2
            y = start_y - index * lead
            self.text(x, y, line, size=size)

    def actor(self, x: float, y: float, label: str) -> None:
        self.ellipse(x, y + 34, 10, 10)
        self.line(x, y + 24, x, y - 6)
        self.line(x - 16, y + 14, x + 16, y + 14)
        self.line(x, y - 6, x - 14, y - 28)
        self.line(x, y - 6, x + 14, y - 28)
        self.centered_text(x, y - 48, label, size=12)

    def include_arrow(self, x1: float, y1: float, x2: float, y2: float, label: str) -> None:
        self.dashed_line(x1, y1, x2, y2)
        angle_x = x2 - x1
        angle_y = y2 - y1
        mag = (angle_x**2 + angle_y**2) ** 0.5 or 1
        ux = angle_x / mag
        uy = angle_y / mag
        px = -uy
        py = ux
        head = [
            (x2, y2),
            (x2 - ux * 14 + px * 5, y2 - uy * 14 + py * 5),
            (x2 - ux * 14 - px * 5, y2 - uy * 14 - py * 5),
        ]
        self.polygon(head, fill=False)
        self.centered_text((x1 + x2) / 2, (y1 + y2) / 2 + 12, label, size=10)

    def build(self) -> bytes:
        return "\n".join(self.ops).encode("latin-1", errors="replace")


def write_pdf(path: Path, content: bytes) -> None:
    objects: list[bytes] = []

    def add_object(body: bytes) -> int:
        objects.append(body)
        return len(objects)

    add_object(b"<< /Type /Catalog /Pages 2 0 R >>")
    add_object(b"<< /Type /Pages /Kids [3 0 R] /Count 1 >>")
    add_object(
        f"<< /Type /Page /Parent 2 0 R /MediaBox [0 0 {PAGE_WIDTH} {PAGE_HEIGHT}] "
        f"/Resources << /Font << /F1 4 0 R /F2 5 0 R >> >> /Contents 6 0 R >>".encode("ascii")
    )
    add_object(b"<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica >>")
    add_object(b"<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica-Bold >>")
    add_object(f"<< /Length {len(content)} >>\nstream\n".encode("ascii") + content + b"\nendstream")

    output = bytearray(b"%PDF-1.4\n%\xe2\xe3\xcf\xd3\n")
    offsets = [0]

    for index, body in enumerate(objects, start=1):
        offsets.append(len(output))
        output.extend(f"{index} 0 obj\n".encode("ascii"))
        output.extend(body)
        output.extend(b"\nendobj\n")

    xref_position = len(output)
    output.extend(f"xref\n0 {len(objects) + 1}\n".encode("ascii"))
    output.extend(b"0000000000 65535 f \n")
    for offset in offsets[1:]:
        output.extend(f"{offset:010d} 00000 n \n".encode("ascii"))

    output.extend(
        (
            f"trailer\n<< /Size {len(objects) + 1} /Root 1 0 R >>\n"
            f"startxref\n{xref_position}\n%%EOF"
        ).encode("ascii")
    )

    path.parent.mkdir(parents=True, exist_ok=True)
    path.write_bytes(output)


def main() -> None:
    pdf = PdfCanvas()
    pdf.line_width(1.2)
    pdf.set_stroke(0.10, 0.16, 0.28)
    pdf.set_fill(1, 1, 1)

    pdf.text(42, 560, "Diagrama de Caso de Uso - PetShop Vida Animal", size=22, font="F2")
    pdf.text(42, 538, "Baseado nas funcionalidades implementadas no sistema atual.", size=11)

    boundary_x = 165
    boundary_y = 96
    boundary_w = 515
    boundary_h = 405

    pdf.set_fill(0.95, 0.97, 1.00)
    pdf.rect(boundary_x, boundary_y, boundary_w, boundary_h, fill=True)
    pdf.text(boundary_x + 18, boundary_y + boundary_h - 22, "Sistema: PetShop Vida Animal", size=14, font="F2")

    pdf.set_stroke(0.10, 0.16, 0.28)
    pdf.actor(78, 410, "Visitante")
    pdf.actor(78, 208, "Cliente")
    pdf.actor(760, 208, "Administrador")

    use_cases = {
        "catalogo": (310, 410, 88, 28, "Visualizar\ncatalogo"),
        "adicionar": (310, 335, 96, 28, "Adicionar item\nao carrinho"),
        "carrinho": (310, 260, 88, 28, "Visualizar\ncarrinho"),
        "login": (310, 185, 74, 26, "Fazer login"),
        "remover": (535, 335, 102, 28, "Remover item\ndo carrinho"),
        "limpar": (535, 260, 84, 26, "Limpar\ncarrinho"),
        "logout": (535, 185, 74, 26, "Fazer logout"),
        "painel": (430, 120, 96, 28, "Acessar painel\nadmin"),
        "metricas": (618, 145, 86, 24, "Visualizar\nmetricas"),
        "catalogo_admin": (618, 92, 110, 24, "Consultar catalogo\nadministrativo"),
    }

    pdf.set_stroke(0.23, 0.33, 0.52)
    pdf.set_fill(1.0, 1.0, 1.0)
    for cx, cy, rx, ry, label in use_cases.values():
        pdf.ellipse(cx, cy, rx, ry, fill=True)
        pdf.centered_text(cx, cy + 3, label, size=11)

    pdf.set_stroke(0.15, 0.21, 0.34)
    left_x = 92
    pdf.line(left_x, 410, 222, 410)
    pdf.line(left_x, 408, 214, 345)
    pdf.line(left_x, 406, 222, 270)
    pdf.line(left_x, 404, 236, 193)
    pdf.line(left_x, 402, 430, 335)
    pdf.line(left_x, 400, 451, 260)

    pdf.line(92, 208, 236, 184)
    pdf.line(92, 204, 456, 185)

    pdf.line(746, 208, 528, 120)
    pdf.line(746, 205, 611, 145)
    pdf.line(746, 202, 611, 92)
    pdf.line(746, 199, 610, 185)

    pdf.set_stroke(0.40, 0.44, 0.58)
    pdf.include_arrow(506, 120, 566, 145, "<<include>>")
    pdf.include_arrow(502, 110, 548, 92, "<<include>>")

    pdf.text(42, 52, "Atores: Visitante, Cliente e Administrador.", size=10)
    pdf.text(42, 38, "Casos de uso administrativos dependem de autenticacao como administrador.", size=10)

    output = Path("docs/diagrama-caso-de-uso.pdf")
    write_pdf(output, pdf.build())
    print(output)


if __name__ == "__main__":
    main()
