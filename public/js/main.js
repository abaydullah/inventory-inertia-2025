
// let sidebars = document.getElementById('sidebar');
// let items = document.querySelectorAll('ul >li >a');

function openBarOnHover () {
    var opensidebarIs = document.getElementById('sidebar').classList.contains('md:w-16');
    if(opensidebarIs){
        opensidebarIs = document.getElementById('sidebar').classList.add('true')
        Openbar();
    }
}
function closeBarOnHover () {
    var opensidebarIs = document.getElementById('sidebar').classList.contains('true');
    if(opensidebarIs){
        opensidebarIs = document.getElementById('sidebar').classList.remove('true')
        Openbar();
    }
}

// document.getElementById("sidebar-area").addEventListener("mouseenter", openBarOnHover);
// document.getElementById("sidebar-area").addEventListener("mouseleave", closeBarOnHover);




function OpenbarM(){
    var sidebar = document.getElementById("sidebar-area");
    if(sidebar.classList.contains('hidden')){
        sidebar.classList.add('block');
        sidebar.classList.remove('hidden');

    }else{
        sidebar.classList.remove('block');
        sidebar.classList.add('hidden');
    }

    let bar = document.getElementById('m-menu-bar').classList;

    let bar1 = document.getElementById('m-menu-bar-1').classList;
    bar1.toggle('rotate-[40deg]');

    bar1.toggle('float-right');
    bar1.toggle('-mt-1');

    let bar3 = document.getElementById('m-menu-bar-3').classList;
    bar3.toggle('-rotate-[40deg]');

    bar3.toggle('float-right');



    if(bar.contains('space-y-2')){
        bar.add('space-y-0');
        bar.remove('space-y-2');
        bar1.add('w-3');
        bar1.remove('w-8');
        bar3.add('w-3');
        bar3.remove('w-8');
    }else{
        bar.remove('space-y-0');
        bar.add('space-y-2');
        bar1.remove('w-3');
        bar1.add('w-8');
        bar3.remove('w-3');
        bar3.add('w-8');
    }

}
// let closeSideBar = document.getElementById('content').addEventListener("click",function(){
//     var sidebar = document.getElementById("sidebar-area");
//     if(!sidebar.classList.contains('hidden')){
//         OpenbarM();
//     }
// });
function openTopBar(){

    let topbar = document.getElementById("topbar");
    if(topbar.classList.contains('hidden')){
        topbar.classList.add('block');
        topbar.classList.remove('hidden');

    }else{
        topbar.classList.remove('block');
        topbar.classList.add('hidden');
    }
    let test = document.getElementById('topbaricon').classList.toggle('-rotate-90');

    console.log(test);
}
function openSubBar(e){
    let subbar = document.getElementById(e);
    if(subbar.classList.contains('hidden')){
        subbar.classList.add('block');
        subbar.classList.remove('hidden');
    }else{
        subbar.classList.remove('block');
        subbar.classList.add('hidden');
    }
    document.getElementById('arrow-'+e).classList.toggle('-rotate-90');
}

function ThemeMode(){
    let mode  = document.querySelector('html').classList.contains('dark');
    mode == true ? (document.querySelector('html').classList.remove('dark'),document.getElementById('theme').setAttribute('name','moon-outline'),document.querySelector('html').classList.add('light')) : (document.querySelector('html').classList.remove('light'),document.getElementById('theme').setAttribute('name','sunny-outline'),document.querySelector('html').classList.add('dark'))

}

function ThemeModeM(){
    let mode  = document.querySelector('html').classList.contains('dark');
    mode == true ? (document.querySelector('html').classList.remove('dark'),document.getElementById('themem').setAttribute('name','moon-outline'),document.querySelector('html').classList.add('light')) : (document.querySelector('html').classList.remove('light'),document.getElementById('themem').setAttribute('name','sunny-outline'),document.querySelector('html').classList.add('dark'))

}
