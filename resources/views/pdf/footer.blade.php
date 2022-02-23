
{{-- Here's the magic. This MUST be inside body tag. Page count / total, centered at bottom of page --}}
<script type="text/php">
    if (isset($pdf)) {
        $text = "pagina {PAGE_NUM} / {PAGE_COUNT}";
        $size = 7;
        $font = $fontMetrics->getFont("Verdana");
        $width = $fontMetrics->get_text_width($text, $font, $size) / 2;
        $x = ($pdf->get_width() - $width) / 2;
        $y = $pdf->get_height() - 35;
        $pdf->page_text($x, $y, $text, $font, $size);
    }
</script>
</body>
</html>
