<?php

namespace App\Policies;

class RolePolicy
{
    protected array $permissions = [
        // Super Admin - Full system access and management
        'superadmin' => [
            // User Management
            'manage_users' => true,
            'create_users' => true,
            'edit_users' => true,
            'delete_users' => true,
            'reset_passwords' => true,
            'bulk_actions' => true,

            // All Entities
            'manage_students' => true,
            'manage_classes' => true,
            'manage_sections' => true,
            'manage_subjects' => true,
            'manage_exams' => true,
            'manage_admit_cards' => true,
            'manage_fees' => true,
            'manage_payments' => true,

            // Operations
            'view_all_reports' => true,
            'export_reports' => true,
            'system_settings' => true,
            'audit_logs' => true,
            'system_backup' => true,

            // Dashboard
            'full_dashboard_access' => true,
            'system_statistics' => true,
        ],

        // Teacher - Exam management, student evaluation, mark entry
        'teacher' => [
            // Student Management (Read mostly)
            'view_students' => true,
            'manage_students' => false,
            'view_class_students' => true,

            // Exam Management (Full)
            'manage_exams' => true,
            'create_exams' => true,
            'edit_exams' => true,
            'delete_exams' => true,
            'view_all_exams' => true,
            'mark_entry' => true,

            // Admit Card Management (Generate for students)
            'view_admit_cards' => true,
            'bulk_generate_admit_cards' => true,
            'download_admit_cards' => true,

            // Subject Management (Read)
            'view_subjects' => true,
            'manage_subjects' => false,

            // Class/Section Management (Read)
            'view_classes' => true,
            'view_sections' => true,
            'manage_classes' => false,
            'manage_sections' => false,

            // Fee Management (Read only)
            'view_fee_reports' => true,
            'manage_fees' => false,

            // Dashboard
            'teacher_dashboard' => true,
            'class_statistics' => true,

            // Reports
            'generate_mark_reports' => true,
            'exam_performance_reports' => true,
        ],

        // Accountant - Fee and payment management
        'accountant' => [
            // Student Management (Minimal)
            'view_students' => true,
            'manage_students' => false,

            // Fee Management (Full)
            'manage_fees' => true,
            'create_fees' => true,
            'edit_fees' => true,
            'update_fee_status' => true,
            'generate_receipts' => true,
            'bulk_fee_operations' => true,

            // Payment Management (Full)
            'manage_payments' => true,
            'record_payments' => true,
            'process_payments' => true,
            'view_payment_history' => true,
            'payment_reports' => true,

            // Financial Reports (Read)
            'fee_collection_reports' => true,
            'payment_reports' => true,
            'monthly_financial_reports' => true,
            'export_financial_reports' => true,

            // Admit Card Access (for fee verification)
            'view_admit_cards' => true,
            'block_admit_cards' => true, // for unpaid fees
            'fee_status_check' => true,

            // Class/Section (Read)
            'view_classes' => true,
            'view_class_students' => true,

            // Dashboard
            'fee_dashboard' => true,
            'financial_statistics' => true,
        ],

        // Student - Admit card download access (optional)
        'student' => [
            // Personal Profile
            'edit_own_profile' => true,
            'view_own_profile' => true,

            // Admit Card Access (Read)
            'view_own_admit_cards' => true,
            'download_own_admit_cards' => true,
            'check_admit_card_eligibility' => true,

            // Academic Information (Read only)
            'view_own_class' => true,
            'view_own_section' => true,
            'view_own_subjects' => true,
            'view_exam_schedule' => true,

            // Fee Information (Read only)
            'view_own_fees' => true,
            'view_payment_history' => true,
            'fee_payment_status' => true,

            // Dashboard
            'student_dashboard' => true,
            'academic_performance' => true,

            // No access to management features
            'manage_students' => false,
            'manage_classes' => false,
            'manage_sections' => false,
            'manage_subjects' => false,
            'manage_exams' => false,
            'manage_admit_cards' => false,
            'manage_fees' => false,
            'manage_payments' => false,
        ],
    ];

    /**
     * Check if a user role has a specific permission.
     */
    public function hasPermission(string $role, string $permission): bool
    {
        return $this->permissions[$role][$permission] ?? false;
    }

    /**
     * Check if user has access to multiple permissions (any of them).
     */
    public function hasAnyPermission(string $role, array $permissions): bool
    {
        foreach ($permissions as $permission) {
            if ($this->hasPermission($role, $permission)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Check if user has access to all specified permissions.
     */
    public function hasAllPermissions(string $role, array $permissions): bool
    {
        foreach ($permissions as $permission) {
            if (!$this->hasPermission($role, $permission)) {
                return false;
            }
        }
        return true;
    }

    /**
     * Get all permissions for a role.
     */
    public function getPermissions(string $role): array
    {
        return $this->permissions[$role] ?? [];
    }

    /**
     * Get all roles that have a specific permission.
     */
    public function getRolesWithPermission(string $permission): array
    {
        $roles = [];
        foreach ($this->permissions as $role => $permissions) {
            if ($this->hasPermission($role, $permission)) {
                $roles[] = $role;
            }
        }
        return $roles;
    }

    /**
     * Get navigation menu items for a role.
     */
    public function getNavigationMenu(string $role): array
    {
        $menu = [];

        // Dashboard - available to all authenticated users
        $menu[] = [
            'name' => 'Dashboard',
            'route' => 'dashboard',
            'permission' => true,
            'icon' => '🏠'
        ];

        // User Management - Only Super Admin
        if ($this->hasPermission($role, 'manage_users')) {
            $menu[] = [
                'name' => 'Users',
                'route' => 'users.index',
                'permission' => true,
                'icon' => '👥'
            ];
        }

        // Student Management
        if ($this->hasPermission($role, 'manage_students') ||
            $this->hasPermission($role, 'view_students')) {
            $menu[] = [
                'name' => 'Students',
                'route' => 'students.index',
                'permission' => true,
                'icon' => '👨‍🎓'
            ];
        }

        // Class & Section Management
        if ($this->hasPermission($role, 'manage_classes')) {
            $menu[] = [
                'name' => 'Classes',
                'route' => 'classes.index',
                'permission' => true,
                'icon' => '🏫'
            ];
            $menu[] = [
                'name' => 'Sections',
                'route' => 'sections.index',
                'permission' => true,
                'icon' => '📚'
            ];
            $menu[] = [
                'name' => 'Subjects',
                'route' => 'subjects.index',
                'permission' => true,
                'icon' => '📖'
            ];
        } elseif ($this->hasPermission($role, 'view_classes')) {
            $menu[] = [
                'name' => 'Classes',
                'route' => 'classes.index',
                'permission' => true,
                'icon' => '🏫'
            ];
            $menu[] = [
                'name' => 'Sections',
                'route' => 'sections.index',
                'permission' => true,
                'icon' => '📚'
            ];
        }

        // Exam Management
        if ($this->hasPermission($role, 'manage_exams') ||
            $this->hasPermission($role, 'view_all_exams')) {
            $menu[] = [
                'name' => 'Exams',
                'route' => 'exams.index',
                'permission' => true,
                'icon' => '📝'
            ];
        }

        // Admit Cards
        if ($this->hasPermission($role, 'manage_admit_cards') ||
            $this->hasPermission($role, 'view_admit_cards') ||
            $this->hasPermission($role, 'download_own_admit_cards')) {
            $menu[] = [
                'name' => 'Admit Cards',
                'route' => 'admit-cards.index',
                'permission' => true,
                'icon' => '📄'
            ];
        }

        // Fee Management
        if ($this->hasPermission($role, 'manage_fees') ||
            $this->hasPermission($role, 'view_fee_reports')) {
            $menu[] = [
                'name' => 'Fees',
                'route' => 'fees.index',
                'permission' => true,
                'icon' => '💰'
            ];
            $menu[] = [
                'name' => 'Monthly Reports',
                'route' => 'fees.monthly-report',
                'permission' => true,
                'icon' => '📊'
            ];
        }

        // Payment Management
        if ($this->hasPermission($role, 'manage_payments') ||
            $this->hasPermission($role, 'view_payment_history')) {
            $menu[] = [
                'name' => 'Payments',
                'route' => 'payments.index',
                'permission' => true,
                'icon' => '💳'
            ];
        }

        return $menu;
    }

    /**
     * Get role hierarchy levels for comparison.
     */
    public function getRoleHierarchy(): array
    {
        return [
            'superadmin' => 4,
            'teacher' => 3,
            'accountant' => 2,
            'student' => 1,
        ];
    }

    /**
     * Check if one role has higher privilege than another.
     */
    public function hasHigherPrivilege(string $role1, string $role2): bool
    {
        $hierarchy = $this->getRoleHierarchy();
        return ($hierarchy[$role1] ?? 0) > ($hierarchy[$role2] ?? 0);
    }

    /**
     * Get available roles for role assignment.
     */
    public function getAssignableRoles(string $userRole): array
    {
        if ($userRole !== 'superadmin') {
            return []; // Only super admin can assign roles
        }

        return [
            'superadmin' => 'Super Admin',
            'teacher' => 'Teacher',
            'accountant' => 'Accountant',
            'student' => 'Student',
        ];
    }
}