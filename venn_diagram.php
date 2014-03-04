<div align="center" class="venn"></div>


<script type="text/javascript" src="d3.min.js"></script>
<script type="text/javascript" src="venn.js"></script>
<script>
// define sets and set set intersections
var sets = <?php echo $sets?>,
    overlaps = <?php echo $overlaps?>;

// get positions for each set
sets = venn.venn(sets, overlaps);

// draw the diagram in the 'simple_example' div
venn.drawD3Diagram(d3.select(".venn"), sets, 600, 600);
</script>
</html>