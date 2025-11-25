
export const AddRow = (dt: any, resource: object) => {
    const rowObj = {...resource, action: ''};
    dt.row.add(rowObj).draw(false);
};

export const EditRow = (dt: any, resource: object, target: HTMLElement) => {
    const rowObj = {...resource, action: ''};
    dt.row(target.closest('tr')).data(rowObj).draw(false);
};

export const DeleteRow = (dt: any, target: HTMLElement ): void => {
    dt.row(target.closest('tr')).remove().draw(false);
};
