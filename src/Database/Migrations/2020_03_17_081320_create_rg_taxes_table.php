<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRgTaxesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('tenant')->create('rg_taxes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();

            //>> default columns
            $table->softDeletes();
            $table->unsignedBigInteger('tenant_id');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            //<< default columns

            //>> table columns
            $table->unsignedBigInteger('project_id')->nullable();
            $table->string('country',3);
            $table->string('name', 100);
            $table->string('code', 100);
            $table->string('display_name', 50);
            $table->string('value', 50);
            $table->enum('based_on', ['item','total']);
            $table->tinyInteger('inclusive')->nullable();

            $table->date('enforceable_from')->nullable()->comment('Date when the tax goes into force');
            $table->date('abolished_on')->nullable()->comment('date when the tax is abolished');

            $table->tinyInteger('on_sale')->nullable();
            $table->enum('on_sale_effect', ['debit', 'credit'])->nullable();
            $table->unsignedBigInteger('on_sale_financial_account_code')->nullable();
            $table->tinyInteger('on_bill')->nullable();
            $table->enum('on_bill_effect', ['debit', 'credit'])->nullable();
            $table->unsignedBigInteger('on_bill_financial_account_code')->nullable();

            $table->index(['tenant_id', 'code'], 'tenant_index');
            $table->unique(['tenant_id', 'code'], 'tenant_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('tenant')->dropIfExists('rg_taxes');
    }
}
