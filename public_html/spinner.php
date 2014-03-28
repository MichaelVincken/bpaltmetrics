<style>
.windows8 {
position: relative;
width: 80px;
height:80px;
}

.windows8 .wBall {
position: absolute;
width: 76px;
height: 76px;
opacity: 0;
-moz-transform: rotate(225deg);
-moz-animation: orbit 7.15s infinite;
-webkit-transform: rotate(225deg);
-webkit-animation: orbit 7.15s infinite;
-ms-transform: rotate(225deg);
-ms-animation: orbit 7.15s infinite;
-o-transform: rotate(225deg);
-o-animation: orbit 7.15s infinite;
transform: rotate(225deg);
animation: orbit 7.15s infinite;
}

.windows8 .wBall .wInnerBall{
position: absolute;
width: 10px;
height: 10px;
background: #000000;
left:0px;
top:0px;
-moz-border-radius: 10px;
-webkit-border-radius: 10px;
-ms-border-radius: 10px;
-o-border-radius: 10px;
border-radius: 10px;
}

.windows8 #wBall_1 {
-moz-animation-delay: 1.56s;
-webkit-animation-delay: 1.56s;
-ms-animation-delay: 1.56s;
-o-animation-delay: 1.56s;
animation-delay: 1.56s;
}

.windows8 #wBall_2 {
-moz-animation-delay: 0.31s;
-webkit-animation-delay: 0.31s;
-ms-animation-delay: 0.31s;
-o-animation-delay: 0.31s;
animation-delay: 0.31s;
}

.windows8 #wBall_3 {
-moz-animation-delay: 0.62s;
-webkit-animation-delay: 0.62s;
-ms-animation-delay: 0.62s;
-o-animation-delay: 0.62s;
animation-delay: 0.62s;
}

.windows8 #wBall_4 {
-moz-animation-delay: 0.94s;
-webkit-animation-delay: 0.94s;
-ms-animation-delay: 0.94s;
-o-animation-delay: 0.94s;
animation-delay: 0.94s;
}

.windows8 #wBall_5 {
-moz-animation-delay: 1.25s;
-webkit-animation-delay: 1.25s;
-ms-animation-delay: 1.25s;
-o-animation-delay: 1.25s;
animation-delay: 1.25s;
}

@-moz-keyframes orbit {
0% {
opacity: 1;
z-index:99;
-moz-transform: rotate(180deg);
-moz-animation-timing-function: ease-out;
}

7% {
opacity: 1;
-moz-transform: rotate(300deg);
-moz-animation-timing-function: linear;
-moz-origin:0%;
}

30% {
opacity: 1;
-moz-transform:rotate(410deg);
-moz-animation-timing-function: ease-in-out;
-moz-origin:7%;
}

39% {
opacity: 1;
-moz-transform: rotate(645deg);
-moz-animation-timing-function: linear;
-moz-origin:30%;
}

70% {
opacity: 1;
-moz-transform: rotate(770deg);
-moz-animation-timing-function: ease-out;
-moz-origin:39%;
}

75% {
opacity: 1;
-moz-transform: rotate(900deg);
-moz-animation-timing-function: ease-out;
-moz-origin:70%;
}

76% {
opacity: 0;
-moz-transform:rotate(900deg);
}

100% {
opacity: 0;
-moz-transform: rotate(900deg);
}

}

@-webkit-keyframes orbit {
0% {
opacity: 1;
z-index:99;
-webkit-transform: rotate(180deg);
-webkit-animation-timing-function: ease-out;
}

7% {
opacity: 1;
-webkit-transform: rotate(300deg);
-webkit-animation-timing-function: linear;
-webkit-origin:0%;
}

30% {
opacity: 1;
-webkit-transform:rotate(410deg);
-webkit-animation-timing-function: ease-in-out;
-webkit-origin:7%;
}

39% {
opacity: 1;
-webkit-transform: rotate(645deg);
-webkit-animation-timing-function: linear;
-webkit-origin:30%;
}

70% {
opacity: 1;
-webkit-transform: rotate(770deg);
-webkit-animation-timing-function: ease-out;
-webkit-origin:39%;
}

75% {
opacity: 1;
-webkit-transform: rotate(900deg);
-webkit-animation-timing-function: ease-out;
-webkit-origin:70%;
}

76% {
opacity: 0;
-webkit-transform:rotate(900deg);
}

100% {
opacity: 0;
-webkit-transform: rotate(900deg);
}

}

@-ms-keyframes orbit {
0% {
opacity: 1;
z-index:99;
-ms-transform: rotate(180deg);
-ms-animation-timing-function: ease-out;
}

7% {
opacity: 1;
-ms-transform: rotate(300deg);
-ms-animation-timing-function: linear;
-ms-origin:0%;
}

30% {
opacity: 1;
-ms-transform:rotate(410deg);
-ms-animation-timing-function: ease-in-out;
-ms-origin:7%;
}

39% {
opacity: 1;
-ms-transform: rotate(645deg);
-ms-animation-timing-function: linear;
-ms-origin:30%;
}

70% {
opacity: 1;
-ms-transform: rotate(770deg);
-ms-animation-timing-function: ease-out;
-ms-origin:39%;
}

75% {
opacity: 1;
-ms-transform: rotate(900deg);
-ms-animation-timing-function: ease-out;
-ms-origin:70%;
}

76% {
opacity: 0;
-ms-transform:rotate(900deg);
}

100% {
opacity: 0;
-ms-transform: rotate(900deg);
}

}

@-o-keyframes orbit {
0% {
opacity: 1;
z-index:99;
-o-transform: rotate(180deg);
-o-animation-timing-function: ease-out;
}

7% {
opacity: 1;
-o-transform: rotate(300deg);
-o-animation-timing-function: linear;
-o-origin:0%;
}

30% {
opacity: 1;
-o-transform:rotate(410deg);
-o-animation-timing-function: ease-in-out;
-o-origin:7%;
}

39% {
opacity: 1;
-o-transform: rotate(645deg);
-o-animation-timing-function: linear;
-o-origin:30%;
}

70% {
opacity: 1;
-o-transform: rotate(770deg);
-o-animation-timing-function: ease-out;
-o-origin:39%;
}

75% {
opacity: 1;
-o-transform: rotate(900deg);
-o-animation-timing-function: ease-out;
-o-origin:70%;
}

76% {
opacity: 0;
-o-transform:rotate(900deg);
}

100% {
opacity: 0;
-o-transform: rotate(900deg);
}

}

@keyframes orbit {
0% {
opacity: 1;
z-index:99;
transform: rotate(180deg);
animation-timing-function: ease-out;
}

7% {
opacity: 1;
transform: rotate(300deg);
animation-timing-function: linear;
origin:0%;
}

30% {
opacity: 1;
transform:rotate(410deg);
animation-timing-function: ease-in-out;
origin:7%;
}

39% {
opacity: 1;
transform: rotate(645deg);
animation-timing-function: linear;
origin:30%;
}

70% {
opacity: 1;
transform: rotate(770deg);
animation-timing-function: ease-out;
origin:39%;
}

75% {
opacity: 1;
transform: rotate(900deg);
animation-timing-function: ease-out;
origin:70%;
}

76% {
opacity: 0;
transform:rotate(900deg);
}

100% {
opacity: 0;
transform: rotate(900deg);
}

}

</style>
<div class="windows8">
<div class="wBall" id="wBall_1">
<div class="wInnerBall">
</div>
</div>
<div class="wBall" id="wBall_2">
<div class="wInnerBall">
</div>
</div>
<div class="wBall" id="wBall_3">
<div class="wInnerBall">
</div>
</div>
<div class="wBall" id="wBall_4">
<div class="wInnerBall">
</div>
</div>
<div class="wBall" id="wBall_5">
<div class="wInnerBall">
</div>
</div>
</div>