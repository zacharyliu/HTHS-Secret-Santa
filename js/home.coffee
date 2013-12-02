$(document).ready( () ->
  #initialize plugins
  try
    $('blockquote').quovolver(500, 6000);
    $.stellar();
  snowStorm.flakesMaxActive = 96;
  snowStorm.flakesMax = 180;
  snowStorm.vMaxX = 12;
  snowStorm.vMaxY = 8;
  snowStorm.snowStick = false;
  snowStorm.snowCharacter = '&bull;'
  #snowStorm.snowColor = '#99ccff';

  #get the height and width
  ymax = $(".home-container").height()
  xmax = $('.home-container').width()


  #set the sky background
  d = new Date();
  h = d.getHours();

  if 6<h<=16 #7AM to 5PM - day
    $('.sky').addClass('day')
  else if 16<h<=18 #5PM to 7PM - sunset
    $('.sky').addClass('sunset')
  else if h>18 || h<=4 #7PM to 5AM - night
    $('.sky').addClass('night')
    ###
    #lights
    for i in [0..20]
      x = Math.random()*xmax
      y = Math.random()*ymax
      colors = ['red','green','blue','yellow']
      color = colors[randomInt(0,3)]
      $(".light-container").append("<div class='light #{color}' style='left:#{x}px;top:#{y}px;position:absolute' data-stellar-ratio='3'>&bull;</div>")
    ###
  else if 4<h<=6 #5AM to 7AM - sunrise
    $('.sky').addClass('sunset')
  else
    $('.sky').addClass('day')
)

randomInt = (min, max) ->
  return Math.floor(Math.random() * (max - min + 1)) + min;
