<div align="center" class="venn">
    <table align = "left" vertical-align="middle" >
        <thead>
            <th>network</th>
            <th>amount</th>
        </thead> 
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
     <?php
     if(count($networks)>2) {
         ?>
     <div id="checkbox" float="right">
         <label><input type="checkbox"> Alternative Visualisation. <a href="http://en.wikipedia.org/wiki/Multidimensional_scaling" target="_blank">MDS</a> vs Greed</label>
     </div>
    <?php
     }
    ?>
</div>


<script type="text/javascript" src="d3.min.js"></script>
<script type="text/javascript" src="venn.js"></script>
<script src="http://www.benfrederickson.com/images/mds.js"></script>
<script src="http://www.numericjs.com/lib/numeric-1.2.6.min.js"></script>

<script>
//Define functions for sets and setintersections.
function getSetIntersections() {
    return <?php echo $overlaps?>;;
}

function getSets() {
    return <?php echo $sets?>;
}


// get positions for each set. Initial layoutfunction.
var sets = venn.venn(getSets(), getSetIntersections(), {layoutFunction: venn.classicMDSLayout});

// draw the diagram in the 'simple_example' div
venn.drawD3Diagram(d3.select(".venn"), sets, 600, 600);
//Execute change function if the checkbox changes.
d3.select("input").on("change", change);

function change() {
    if(this.checked) {
        var sets = venn.venn(getSets(), getSetIntersections());
        venn.updateD3Diagram(d3.select(".venn"),sets);
    } else {
        var sets = venn.venn(getSets(), getSetIntersections(),{layoutFunction: venn.classicMDSLayout});
        venn.updateD3Diagram(d3.select(".venn"),sets);
    }
};


</script>
</html>