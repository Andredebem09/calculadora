<?php

namespace App\Http\Controllers;

use App\Services\CalculatorService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CalculatorController extends Controller
{
    protected CalculatorService $calculatorService;

    public function __construct(CalculatorService $calculatorService)
    {
        $this->calculatorService = $calculatorService;
    }

    public function index()
    {
        $stack = Session::get('calculator_stack', []);
        
        foreach ($stack as $value) {
            $this->calculatorService->push($value);
        }

        $result = $this->calculatorService->peek();

        return view('calculator.index', compact('result', 'stack'));
    }

    public function calculate(Request $request)
    {
        $request->validate([
            'operand1' => 'nullable|numeric',
            'operand2' => 'nullable|numeric',
            'operation' => 'required|string|in:add,subtract,multiply,divide,clear',
        ]);

        $inputOperand1 = $request->input('operand1');
        $inputOperand2 = $request->input('operand2');
        $operation = $request->input('operation');

        $stackFromSession = Session::get('calculator_stack', []);
        foreach ($stackFromSession as $value) {
            $this->calculatorService->push($value);
        }

        if ($operation === 'clear') {
            $this->calculatorService->clearStack();
            Session::forget('calculator_stack');
            return redirect()->route('calculator.index')->with('message', 'Calculadora limpa!');
        }
        
        $operand1 = null;
        $operand2 = null;
        $result = null;

        // Tenta obter o primeiro numero
        if ($inputOperand1 !== null && is_numeric($inputOperand1)) {
            $operand1 = (float)$inputOperand1;
        } elseif ($this->calculatorService->peek() !== null) {
            $operand1 = $this->calculatorService->pop();
        } else {
             return redirect()->back()->withErrors(['error' => 'É necessário fornecer um operando para a operação.']);
        }

        if ($inputOperand2 !== null && is_numeric($inputOperand2)) {
            $operand2 = (float)$inputOperand2;
        } elseif ($this->calculatorService->peek() !== null) {
            $operand2 = $this->calculatorService->pop();
        } else {
           
            return redirect()->back()->withErrors(['error' => 'É necessário fornecer dois operandos para esta operação.']);
        }
        
        try {
            switch ($operation) {
                case 'add':
                    $result = $this->calculatorService->add($operand1, $operand2);
                    break;
                case 'subtract':
                    $result = $this->calculatorService->subtract($operand1, $operand2);
                    break;
                case 'multiply':
                    $result = $this->calculatorService->multiply($operand1, $operand2);
                    break;
                case 'divide':
                    $result = $this->calculatorService->divide($operand1, $operand2);
                    if ($result === "Cannot divide by zero") {
                        return redirect()->back()->withErrors(['error' => $result]);
                    }
                    break;
                default:
                    return redirect()->back()->withErrors(['error' => 'Operação inválida.']);
            }

            $this->calculatorService->push($operand1);
            $this->calculatorService->push($operand2);
            $this->calculatorService->push($result);

            return redirect()->route('calculator.index')->with('result', $result);

        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Erro na operação: ' . $e->getMessage()]);
        }
    }
}