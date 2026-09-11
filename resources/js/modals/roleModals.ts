import { ShowModal, LoadingModal } from '@/utils/SwalWrapper';
import { RoleService } from '@/services/role.service';
import { Header, Input } from "@/components/modal";
import { t } from '@/utils/i18n';

export async function createRoleModal() {

    const options = {
        label: t('roles.fields.name'),
        placeholder: t('roles.placeholders.name'),
        grid: 'input-create-role',
        id: 'role-name-input',
    };

    return ShowModal({
        title: Header(t('roles.modals.create')),
        html: `${Input(options)}`,
        preConfirm: async () => {
            const roleName = document.getElementById('role-name-input') as HTMLInputElement;
            if (!roleName) throw new Error(t('common.errors.required'));
            const { data } = await RoleService.create({ role: roleName.value.trim() });
            return data;
        }
    });
}

export async function editRoleModal(id: string) {
    await LoadingModal(t('roles.loading'));
    const { data } = await RoleService.fetch(id);
    const role = data.data;

    const options = {
        label: t('roles.fields.name'),
        placeholder: t('roles.placeholders.name'),
        grid: 'input-edit-role',
        id: role.uuid,
        value: role.name,
    };

    return ShowModal({
        title: Header(t('roles.modals.edit')),
        html: `${Input(options)}`,
        preConfirm: async () => {
            const roleName = document.getElementById(id) as HTMLInputElement;
            if (!roleName) throw new Error(t('common.errors.required'));
            const { data } = await RoleService.update(id, { role: roleName.value.trim() });
            return data;
        },
    });
}

export const editRolePermissionsModal = async (id: string) => {
    await LoadingModal(t('roles.loading'));
    const { data } = await RoleService.fetchWithPermissions(id);
    const role = data.data;

    const modalContent = await generatePermissionsModalContent(role);

    return ShowModal({
        title: Header(t('roles.modals.edit_permissions')),
        html: modalContent,
        confirmButtonText: t('common.actions.save_changes'),
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
            const actionLabel = t(`common.actions.${action}`);
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
        title: Header(t('roles.modals.delete'), 'danger'),
        html: `<span style="font-size: 0.85rem;">${t('roles.modals.delete_confirm')}</span>`,
        confirmButtonText: t('common.actions.delete'),
        preConfirm: async () => {
            const { data } = await RoleService.delete(id);
            return data;
        },
    });
}
