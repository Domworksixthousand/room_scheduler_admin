
    //start
  //fab 
    document.addEventListener('DOMContentLoaded', function() {
        const fab = document.getElementById("fabButton");
        const dial = document.getElementById("speedDial");
        const icon = document.getElementById("fabIcon");

        fab.addEventListener("click",()=>{

        dial.classList.toggle("show");
        icon.classList.toggle("rotate");

        });
    });
//end


  //start
  //tooltip
   document.addEventListener('DOMContentLoaded',function() {
    const tooltip = document.querySelectorAll('.fab-item');
    const tooltipList = [...tooltip].map(tooltipEl => new bootstrap.Tooltip(tooltipEl));
    const tooltip2 = document.querySelectorAll('.fab-main');
    const tooltipList2 = [...tooltip2].map(tooltipEl => new bootstrap.Tooltip(tooltipEl));
  });
  //stop
