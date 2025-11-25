import dayjs from "dayjs";
import { SuccessModal } from "@/utils/SwalWrapper.js";
import {
    createRoleModal, deleteRoleModal,
    editRoleModal,
    editRolePermissionsModal
} from "@/modals/roleModals.js";

(function () {
    const grid = document.getElementById('roles-grid');
    const headerCol = grid.querySelector('.col-xl-12');
    const template = document.getElementById('role-card-template');
    const searchInput = grid.querySelector('input[placeholder="Search Role"]');
    const searchBtn   = document.getElementById('search-team-member');

    function filterRoles(query = '') {
        const q = query.trim().toLowerCase();
        grid.querySelectorAll('.role-card').forEach(card => {
            const name = card.querySelector('.role-name')?.textContent.toLowerCase() ?? '';
            card.style.display = name.includes(q) ? '' : 'none';
        });
    }

    // Live filter on typing (debounced 200 ms)
    let timer;
    searchInput?.addEventListener('input', e => {
        clearTimeout(timer);
        timer = setTimeout(() => filterRoles(e.target.value), 200);
    });

    // Search‑button acts once (for copy/paste users)
    searchBtn?.addEventListener('click', () => filterRoles(searchInput.value));

    grid.addEventListener('click', async (e) => {
        const btn = e.target.closest('[class*="action-"]');
        if (!btn) return;

        const card   = btn.closest('.role-card');
        const roleId = card?.dataset.roleId;
        if (!roleId) return;

        if (btn.classList.contains('action-edit')) return editRole(roleId, card);
        if (btn.classList.contains('action-delete')) return deleteRole(roleId, card);
        if (btn.classList.contains('action-edit-permissions')) return editRolePermissions(roleId, card);
    });

    document.querySelector('#add-new-role')?.addEventListener('click', async () => {
        try {
            const { status, value } = await createRoleModal();
            if (status === 'dismissed') return;
            const role = value.data;

            SuccessModal('Role Created', 'Role created successfully')
                .then(() => {
                    addRoleCard({
                        id: role.uuid,
                        name: role.name,
                        avatar: role.avatar ?? '/assets/images/faces/placeholder.jpg',
                        members: 0,
                        createdAt: dayjs(role.created_at).format('YYYY/MM/DD'),
                    });
                });
        } catch (err) {
            // Interceptor handler the error
        }
    });

    function addRoleCard(role) {
        // Clone y update template
        const clone = template.content.firstElementChild.cloneNode(true);

        clone.dataset.roleId = role.id;
        clone.querySelector('.role-avatar').src = role.avatar;
        clone.querySelector('.role-name').textContent = role.name.toUpperCase();
        clone.querySelector('.role-name-upper').textContent = role.name.toUpperCase();
        clone.querySelector('.role-members').textContent = role.members;
        clone.querySelector('.role-created-at').textContent = role.createdAt;

        // Insert appends the header (.col-xl-12)
        if (headerCol && headerCol.parentNode === grid) {
            headerCol.insertAdjacentElement('afterend', clone);
        } else {
            grid.prepend(clone);
        }

        // Refresh lucide js to render clone icons
        if (window.lucide && typeof window.lucide.createIcons === 'function') {
            window.lucide.createIcons({ root: clone });
        }

        // Re‑apply current search filter so new card respects it
        filterRoles(searchInput.value);
    }

    function editRoleCard(role) {
        const card = document.querySelector(`[data-role-id="${role.uuid}"]`);
        card.querySelector('.role-name').textContent = role.name.toUpperCase();
        card.querySelector('.role-name-upper').textContent = role.name.toUpperCase();
    }

    async function editRole(id) {
        try {
            const { status, value } = await editRoleModal(id);
            if (status === 'dismissed') return;
            const role = value.data;
            SuccessModal('Role Edited', 'Role edited successfully')
                .then(() => editRoleCard(role));
        } catch (err) {
            // Interceptor shows the error modal
        }
    }

    async function editRolePermissions(id) {
        try {
            const { status } = await editRolePermissionsModal(id);
            if (status === 'dismissed') return
            await SuccessModal('Role Edited', 'Role permissions edited successfully');
        } catch (err) {
            // Interceptor shows the error modal
        }
    }

    async function deleteRole(id, card) {
        try {
            const { status } = await deleteRoleModal(id);
            if (status === 'dismissed') return;
            SuccessModal('Role Deleted', 'Role deleted successfully')
                .then(() => card.remove());
        } catch (err) {
            // Interceptor shows the error modal
        }
    }
})();
