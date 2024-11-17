<?php

namespace App\Http\Controllers;

use App\Models\ExpenseCategory;
use App\Models\Expenses;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    public function showCategories()
    {
        $categories = ExpenseCategory::all();
        return response()->json($categories);
    }

    public function storeCategory(Request $request)
    {
        $request->validate([
            'expense_name' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
        ]);

        ExpenseCategory::create([
            'expense_name' => $request->expense_name,
            'description' => $request->description,
        ]);

        return redirect()->route('expense-category')
            ->with('success', 'Expense category created successfully!');
    }

    public function editCategoryForm($id)
    {
        $category = ExpenseCategory::findOrFail($id);
        return view('expense-categories.edit', compact('category'));
    }

    public function updateCategory(Request $request, $id)
    {
        $category = ExpenseCategory::findOrFail($id);

        $request->validate([
            'expense_name' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
        ]);

        $category->update([
            'expense_name' => $request->expense_name,
            'description' => $request->description,
        ]);

        return redirect()->route('expense-categories.index')
            ->with('success', 'Expense category updated successfully!');
    }

    public function deleteCategory($id)
    {
        try {
            $data = ExpenseCategory::findOrFail($id);
            $data->delete();
            return response()->json(['message' => 'Category deleted successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error deleting category', 'error' => $e->getMessage()], 500);
        }
    }

    public function showExpense()
    {
        $data = Expenses::all();
        return response()->json($data);
    }

    public function storeExpense(Request $request)
    {
        $request->validate([
            'expense_type' => 'required|string',
            'amount' => 'required|integer',
            'entry_date' => 'required|date',

        ]);

        Expenses::create([
            'expense_type' => $request->expense_type,
            'amount' => $request->amount,
            'entry_date' => $request->entry_date,

        ]);

        return redirect()->route('expense')
            ->with('success', 'Expense created successfully!');
    }

    public function updateExpense(Request $request, $id)
    {
        $category = Expenses::findOrFail($id);

        $request->validate([
            'expense_type' => 'required|string',
            'amount' => 'required|integer',
            'entry_date' => 'required|date',
        ]);

        $category->update([
            'expense_type' => $request->expense_type,
            'amount' => $request->amount,
            'entry_date' => $request->entry_date,
        ]);

        return redirect()->route('expense')
            ->with('success', 'Expense updated successfully!');
    }

    public function deleteExpense($id)
    {
        try {
            $data = Expenses::findOrFail($id);
            $data->delete();
            return response()->json(['message' => 'Expense deleted successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error deleting expense', 'error' => $e->getMessage()], 500);
        }
    }
}
