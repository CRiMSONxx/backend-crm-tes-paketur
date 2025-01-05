import "primereact/resources/themes/lara-light-cyan/theme.css";
import { useState } from 'react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { formatDate } from '@/Utils/FormatDate';
import { Head, router } from '@inertiajs/react';
import { DataTable } from 'primereact/datatable';
import { Column } from 'primereact/column';
import { InputText } from 'primereact/inputtext';
import { FilterMatchMode } from 'primereact/api';
import { Button } from 'primereact/button';
export default function Dashboard({ companies,totalCompanies }) {
    const [lazyState, setLazyState] = useState({
        first: 0,
        rows: 5,
        page: 1,
        sortField: null,
        sortOrder: null
    });
    const paginatorLeft = <Button type="button" icon="pi pi-refresh" text />;
    const paginatorRight = <Button type="button" icon="pi pi-download" text />;
    const [filters, setFilters] = useState({
        global: { value: null, matchMode: FilterMatchMode.CONTAINS },
        cname: { value: null, matchMode: FilterMatchMode.STARTS_WITH },
        cphone_number: { value: null, matchMode: FilterMatchMode.STARTS_WITH },
        cemail: { value: null, matchMode: FilterMatchMode.STARTS_WITH }
    });
    const [globalFilterValue, setGlobalFilterValue] = useState('');
    const onGlobalFilterChange = (e) => {
        const value = e.target.value;
        let _filters = { ...filters };
        _filters['global'].value = value;
        setFilters(_filters);
        setGlobalFilterValue(value);
    };
    const onPage = (event) => {
        setLazyState(event);
        router.get(
            route('company.list'), 
            { page: event.page + 1, per_page: event.rows },
            { preserveState: true, preserveScroll: true }
        );
    };
    const renderHeader = () => {
        return (
            <div className="flex justify-between">
                <h3 className="text-xl">Company List</h3>
                <span className="p-input-icon-left">
                    <i className="pi pi-search" />
                    <InputText
                        value={globalFilterValue}
                        onChange={onGlobalFilterChange}
                        placeholder="Search..."
                    />
                </span>
            </div>
        );
    };
    const header = renderHeader();
    return (
        <AuthenticatedLayout
            header={
                <h2 className="text-xl font-semibold leading-tight text-gray-800">
                    Companies
                </h2>
            }
        >
            <Head title="Companies" />
            <div className="py-12">
                <div className="mx-auto max-w-7xl sm:px-6 lg:px-8">
                    <div className="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                        <div className="p-6">
                            <DataTable 
                                value={companies.data}
                                lazy
                                first={lazyState.first}
                                rows={5}
                                totalRecords={totalCompanies}
                                onPage={onPage}
                                paginator
                                dataKey="id"
                                filters={filters}
                                filterDisplay="menu"
                                globalFilterFields={['cname', 'cphone_number', 'cemail']}
                                header={header}
                                emptyMessage="No companies found."
                                className="p-datatable-lg"
                                paginatorTemplate="RowsPerPageDropdown FirstPageLink PrevPageLink CurrentPageReport NextPageLink LastPageLink"
                                currentPageReportTemplate="{first} to {last} of {totalRecords}" paginatorLeft={paginatorLeft} paginatorRight={paginatorRight}
                            >
                                <Column field="cname" header="Name" filter sortable />
                                <Column field="cphone_number" header="Phone" filter sortable />
                                <Column field="cemail" header="Email" filter sortable />
                                <Column field="employees_count" header="Employees" sortable />
                                <Column 
                                    field="created_at" 
                                    header="Created At" 
                                    sortable 
                                    body={(rowData) => formatDate.ddmmyyyy(rowData.created_at)}
                                />
                            </DataTable>
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}