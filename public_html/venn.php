<!DOCTYPE html> 
<html> 
  <head> 
    <script type="text/javascript" src="d3.min.js"></script> 
  </head> 
  <body> 
    <script type="text/javascript"> 
 
var w = 1500,
    h = 1000;
 
 
var svg = d3.select("body").append("svg:svg")
    .attr("width", w)
    .attr("height", h);
    
var tooltip = d3.select("body")
    .append("div")
    .style("position", "absolute")
    .style("z-index", "10")
    .style("visibility", "hidden")
    .style("background","lightblue")
    .style("top","600px")
    .style("left","20px");
 
var circle1 = svg.append("svg:circle")
    .attr("cx", 350)
    .attr("cy", 200)
    .attr("r", 200)
    .attr("id","circle1")
    .style("fill", "red")
    .style("fill-opacity", ".5");
    
 
var circle2 = svg.append("svg:circle")
    .attr("cx", 550)
    .attr("cy", 200)
    .attr("r", 200)
    .attr("id","circle3")
    .style("fill", "blue")
    .style("fill-opacity", ".5");
 
var circle3 = svg.append("svg:circle")
    .attr("cx", 450)
    .attr("cy", 350)
    .attr("r", 200)
    .attr("id","circle2")
    .style("fill", "green")
    .style("fill-opacity", ".5");
 

    d3.selectAll("circle")
    .on("mouseover", function(){return tooltip.style("visibility", "visible");})
    .on("mousemove", update)
    .on("mouseout", function(){return tooltip.style("visibility", "hidden");});

    
function update() {
    //var x = event.pageY-10;
    //var y = event.pageX+10; 
    //tooltip.style("top", (x)+"px").style("left",(y)+"px");
    x = event.pageX-10;
    y = event.pageY-10;
    var on1 = new Boolean();
    
    on1 = inside_circle(x,y,circle1.attr("cx"),circle1.attr("cy"),circle1.attr("r"));
    on2 = inside_circle(x,y,circle2.attr("cx"),circle2.attr("cy"),circle2.attr("r"));
    on3 = inside_circle(x,y,circle3.attr("cx"),circle3.attr("cy"),circle3.attr("r"));
    on_area_all = (on1 && on2 && on3);
    on_area_1_2 = (on1 && on2 && !on3);
    on_area_1_3 = (on1 && !on2 && on3);
    on_area_2_3 = (!on1 && on2 && on3);
    on_area_1 = (on1 && !on2 && !on3);
    on_area_2 = (!on1 && on2 && !on3);
    on_area_3 = (!on1 && !on2 && on3);
    if(on_area_all) {
        tooltip.text("auteur: Erik Duval \n title: het is mooi geweest");
    } else  if(on_area_1_2){
        tooltip.text("on_area_1_2");
    } else if(on_area_1_3) {
        tooltip.text("on_area_1_3");
    } else  if(on_area_2_3){
        tooltip.text("on_area_2_3");
    } else if(on_area_1) {
        tooltip.text("on_area_1");
    } else  if(on_area_2){
        tooltip.text("on_area_2");
    } else if(on_area_3) {
        tooltip.text("on_area_3");
    } else {
        tooltip.text("no");
    }

}

function inside_circle(x,y,cx,cy,r) {
    var dx = Math.abs(x-cx);
    var dy = Math.abs(y-cy);
    var R = r;
    
    if(dx>R) return false;
    if(dy>R) return false;
    if((dx + dy) <= R) return true;
    
    if((Math.pow(dx,2) + Math.pow(dy,2)) <= Math.pow(R,2)) return true;     
    return false;
    
    
    
    
}


    </script> 
  </body> 
</html>
