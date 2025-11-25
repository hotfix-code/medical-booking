import { ShowModal, LoadingModal } from '@/utils/SwalWrapper';
import { RoleService } from '@/services/role.service';
import { Header, Input } from "@/components/modal";

export async function createRoleModal() {

    const options = {
        label: 'Role name',
        placeholder: 'Role name',
        grid: 'input-create-role',
        id: 'role-name-input',
    };

    return ShowModal({
        title: Header('Create a new role?'),
        html: `${Input(options)}`,
        preConfirm: async () => {
            const roleName = document.getElementById('role-name-input') as HTMLInputElement;
            if (!roleName) throw new Error('Required');
            const { data } = await RoleService.create({ role: roleName.value.trim() });
            return data;
        }
    });
}

export async function editRoleModal(id: string) {
    await LoadingModal('Loading Role data...');
    const { data } = await RoleService.fetch(id);
    const role = data.data;

    const options = {
        label: 'Role name',
        placeholder: 'Role name',
        grid: 'input-edit-role',
        id: role.uuid,
        value: role.name,
    };

    return ShowModal({
        title: Header('Edit the role?'),
        html: `${Input(options)}`,
        preConfirm: async () => {
            const roleName = document.getElementById(id) as HTMLInputElement;
            if (!roleName) throw new Error('Required');
            const { data } = await RoleService.update(id, { role: roleName.value.trim() });
            return data;
        },
    });
}

export const editRolePermissionsModal = async (id: string) => {
    await LoadingModal('Loading Role data...');
    const { data } = await RoleService.fetchWithPermissions(id);
    const role = data.data;

    const modalContent = await generatePermissionsModalContent(role);

    return ShowModal({
        title: Header('Edit Role Permissions'),
        html: modalContent,
        confirmButtonText: 'Save Changes',
        preConfirm: async () => {
            const permissions = document.querySelectorAll('.form-check-input');
            const permissionsData: string[] = [];
            permissions.forEach((permission: any) => {
                if (permission.checked) {
                    permissionsData.push(permission.value);
                }
            })
            const payload: object = {permissions: permissionsData}
            const { data } = await RoleService.updatePermissions(id, payload);
            return data;
        },
    });
};

export async function generatePermissionsModalContent(role: any) {
    const groupedPermissions: any = {};
    const permissions = role.permissionsSelected;
    const isAdminRole:boolean = role.name === 'super-admin';

    permissions.forEach((permission: any) => {
        const category = permission.formatName;
        if (!groupedPermissions[category]) {
            groupedPermissions[category] = [];
        }
        groupedPermissions[category].push(permission);
    });

    let modalHtml = '<div class="row gx-0">';

    Object.keys(groupedPermissions).forEach(category => {
        const categoryPermissions = groupedPermissions[category];

        modalHtml += `
                <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 permission-card">
                    <div class="card custom-card">
                        <div class="card-header justify-content-between border-0">
                            <div class="card-title">
                                ${category}
                            </div>
                        </div>
                        <div class="card-body">
            `;

        categoryPermissions.forEach((permission: any) => {
            const action = permission.name.split('.')[1];
            const actionLabel = action.charAt(0).toUpperCase() + action.slice(1);
            const isChecked = permission.selected || isAdminRole ? 'checked' : '';
            const isDisabled = isAdminRole ? 'disabled' : '';

            modalHtml += `
                    <div class="form-check form-switch py-1">
                        <input class="form-check-input" type="checkbox" role="switch"
                               id="${permission.uuid}" value="${permission.name}" ${isChecked} style="cursor: pointer;" ${isDisabled}>
                        <label class="form-check-label" for="${permission.uuid}" style="cursor: pointer;">${actionLabel}</label>
                    </div>
                `;
        });

        modalHtml += `
                        </div>
                        <div class="card-footer d-none border-top-0">
                        </div>
                    </div>
                </div>
            `;
    });

    modalHtml += '</div>';

    return modalHtml;
}

export async function deleteRoleModal(id: string) {
    return ShowModal({
        title: Header('Delete role?', 'danger'),
        html: `<span style="font-size: 0.85rem;">Are you sure you want to delete the role?</span>`,
        confirmButtonText: 'Delete',
        preConfirm: async () => {
            const { data } = await RoleService.delete(id);
            return data;
        },
    });
}
