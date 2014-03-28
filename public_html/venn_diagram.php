<div align="center" class="venn">
    <table align = "left" vertical-align="middle" >
        <theader>
            <th>network</th>
            <th>amount</th>
        </theader> 
        <tbody>
            <?php
            foreach($set_records as $name =>$array) {
                ?>
                <tr><td>
                <?php
                echo $name;
                echo "</td>";
                echo "<td class='tooltip'>";
                echo count($array);
                echo "</td></tr>";
            }
                
            ?>
        </tbody>
     </table>
</div>


<script type="text/javascript" src="d3.min.js"></script>
<script type="text/javascript" src="venn.js"></script>
<script src="http://www.benfrederickson.com/images/mds.js"></script>
<script src="http://www.numericjs.com/lib/numeric-1.2.6.min.js"></script>

<script>
// define sets and set set intersections
var sets = <?php echo $sets?>,
    overlaps = <?php echo $overlaps?>;

// get positions for each set
sets = venn.venn(sets, overlaps, {layoutFunction: venn.classicMDSLayout});

// draw the diagram in the 'simple_example' div
venn.drawD3Diagram(d3.select(".venn"), sets, 600, 600);
</script>
</html>