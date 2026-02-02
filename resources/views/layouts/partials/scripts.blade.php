@push('scripts')
<script>

const STATE_KEY = 'sidebarState';

const defaultState = {
    collapsed: false,
    hidden: false,
};

document.addEventListener('DOMContentLoaded', function () {
    applyState();

    // enable transition AFTER first paint
    requestAnimationFrame(() => {
        document.documentElement.classList.remove('no-transition');
    });
});


let state = loadState();
const sidebar = document.getElementById('sidebar');
const labels = document.querySelectorAll('.menu-label');
const toggleSidebar = document.getElementById('toggleSidebar');
const toggleLabel = document.getElementById('toggleLabel');
const main = document.getElementById('main-content');
const footer = document.getElementById('footer_app');
    
function loadState() {
    return {
        ...defaultState,
        ...JSON.parse(localStorage.getItem(STATE_KEY) || '{}')
    };
}

function saveState(state) {
    localStorage.setItem(STATE_KEY, JSON.stringify(state));
}

function applyState() {
    
    sidebar.classList.toggle('w-64', !state.collapsed);
    sidebar.classList.toggle('w-20', state.collapsed);

    main.classList.toggle('md:ml-64', !state.collapsed);
    main.classList.toggle('md:ml-20', state.collapsed);
    
    footer.classList.toggle('md:ml-64', !state.collapsed);
    footer.classList.toggle('md:ml-20', state.collapsed);

    labels.forEach(label => {
        label.classList.toggle('hidden', state.collapsed);
    });
    
    toggleLabel.children[0].textContent = !state.collapsed ? 'menu' : 'menu_open';

    // RESET STATE SAAT RESIZE (INI PENTING)
    window.addEventListener('resize', () => {
        if (window.innerWidth >= 768) {
            // Desktop: pastikan sidebar visible
            sidebar.classList.remove('-translate-x-full');
        } else {
            // Mobile: pastikan sidebar full & label tampil
            sidebar.classList.remove('w-20');
            sidebar.classList.add('w-64');

            labels.forEach(label => label.classList.remove('hidden'));
        }
    });
    
}

// MOBILE: toggle sidebar (slide)
toggleSidebar?.addEventListener('click', () => {
    state.hidden = !state.hidden;
    sidebar.classList.toggle('-translate-x-full', !state.hidden);
    sidebar.classList.remove('w-20');
    sidebar.classList.add('w-64');
    labels.forEach(label => label.classList.remove('hidden'));
});

// DESKTOP: toggle label (collapse)
toggleLabel?.addEventListener('click', () => {
    state.collapsed = !state.collapsed;
    saveState(state);
    applyState();
});
</script>
@endpush