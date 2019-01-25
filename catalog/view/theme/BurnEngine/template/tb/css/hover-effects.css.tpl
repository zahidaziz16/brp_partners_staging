.tb_overlay,
.tb_flip
{
  position: relative;
  -webkit-perspective: 800px;
          perspective: 800px;
}
.tb_flip {
  background: transparent !important;
  -webkit-transition-delay: 0.6s;
          transition-delay: 0.6s;
}
.tb_flip:hover {
  z-index: 4;
  -webkit-transition-delay: 0s;
          transition-delay: 0s;
}
.tb_overlay .tb_front,
.tb_overlay .tb_back,
.tb_flip .tb_front,
.tb_flip .tb_back
{
  -webkit-backface-visibility: hidden;
          backface-visibility: hidden;
}
.tb_overlay .tb_front,
.tb_overlay .tb_back
{
  -webkit-transition: all 0.6s ease-out;
          transition: all 0.6s ease-out;
}
.tb_flip .tb_front,
.tb_flip .tb_back
{
  -webkit-transition: all 0.5s ease-in-out;
          transition: all 0.5s ease-in-out;
}
.tb_overlay .tb_front,
.tb_flip .tb_front
{
  z-index: 3;
  position: relative;
}
.tb_overlay .tb_back,
.tb_flip .tb_back
{
  z-index: 2;
  position: absolute;
  top: 0;
  left: 0;
  margin: 0 !important;
}
.tb_overlay:hover .tb_back,
.tb_flip:hover .tb_back
{
  z-index: 4;
}
.tb_overlay:not(:hover) .tb_back *,
.tb_flip:not(:hover) .tb_back *
{
  pointer-events: none;
}
/*.tb_overlay:hover .tb_front,*/
.tb_overlay .tb_back
{
  opacity: 0;
}
.tb_overlay .tb_front,
.tb_overlay:hover .tb_back
{
  opacity: 1;
}
.tb_flip .tb_front {
  -webkit-transform: rotateY(0deg);
          transform: rotateY(0deg);
}
.tb_flip:hover .tb_front {
  -webkit-transform: rotateY(180deg);
          transform: rotateY(180deg);
}
.tb_flip .tb_back {
  -webkit-transform: rotateY(-180deg);
          transform: rotateY(-180deg);
}
.tb_flip:hover .tb_back {
  -webkit-transform: rotateY(0);
          transform: rotateY(0);
}
