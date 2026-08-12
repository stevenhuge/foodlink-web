<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Fix Supabase Advisor Warning: "Public Can Execute SECURITY DEFINER Function"
        // rls_auto_enable() is created by Supabase, we switch it to SECURITY INVOKER
        // and revoke EXECUTE from anon and authenticated roles.
        
        // This is wrapped in try-catch in case it's run on a non-postgres/supabase local environment
        try {
            DB::statement('REVOKE EXECUTE ON FUNCTION public.rls_auto_enable() FROM PUBLIC, anon, authenticated;');
            DB::statement('ALTER FUNCTION public.rls_auto_enable() SECURITY INVOKER;');
        } catch (\Exception $e) {
            // Ignore if function doesn't exist (e.g. running locally without Supabase)
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        try {
            DB::statement('ALTER FUNCTION public.rls_auto_enable() SECURITY DEFINER;');
            DB::statement('GRANT EXECUTE ON FUNCTION public.rls_auto_enable() TO PUBLIC;');
        } catch (\Exception $e) {
            // Ignore
        }
    }
};
