 const submenuItems = document.querySelectorAll('.has-submenu');
    submenuItems.forEach(item=>{
        item.addEventListener('click', ()=>{
            item.classList.toggle('active');
        });
    });

    // profile popup
    const profileBtn = document.getElementById('profileBtn');
    const profilePopup = document.getElementById('profilePopup');

    profileBtn.addEventListener('click', (e)=>{
        e.stopPropagation();
        profilePopup.style.display = profilePopup.style.display === 'block' ? 'none' : 'block';
    });

    document.addEventListener('click', ()=>{
        profilePopup.style.display = 'none';
    });