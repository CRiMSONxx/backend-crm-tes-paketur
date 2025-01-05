import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, usePage } from '@inertiajs/react';
import DeleteUserForm from '../Partials/DeleteUserForm';
import UpdatePasswordForm from '../Partials/UpdatePasswordForm';
import UpdateProfileInformationForm from '../Partials/UpdateProfileInformationForm';
export default function Edit({employee}) {
    const user = usePage().props.auth.user; // get editor auth
    console.log(user);
    return (
        <AuthenticatedLayout
            header={<h2 className="text-xl font-semibold leading-tight text-gray-800">Employee's Profile</h2>}>
            <Head title="Profile" />
            <div className="py-12">
                <div className="mx-auto max-w-7xl space-y-6 sm:px-6 lg:px-8">
                    <div className="bg-white p-4 shadow sm:rounded-lg sm:p-8">
                        {/* allow super admin to edit everything */}
                        {user.is_super || employee.is_manager ? (
                            <UpdateProfileInformationForm employee={employee} className="max-w-xl" />
                        ) : (
                            <div className="max-w-xl">
                                <h3>Employee Details</h3>
                                <p>Name: {employee.name}</p>
                                <p>Email: {employee.email}</p>
                                {/* Add other employee details as needed */}
                            </div>
                        )}
                    </div>
                    {(user.is_super || employee.is_manager) && (
                        <>
                            <div className="bg-white p-4 shadow sm:rounded-lg sm:p-8">
                                <UpdatePasswordForm className="max-w-xl" />
                            </div>
                            <div className="bg-white p-4 shadow sm:rounded-lg sm:p-8">
                                <DeleteUserForm className="max-w-xl" />
                            </div>
                        </>
                    )}
                </div>
            </div>
        </AuthenticatedLayout>
    );
}