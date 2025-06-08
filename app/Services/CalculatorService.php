<?php

namespace App\Services;

class CalculatorService
{
    protected array $stack = [];

    /**
     * Pushes a value onto the stack.
     *
     * @param mixed $value
     * @return void
     */
    public function push($value): void
    {
        $this->stack[] = $value;
    }

    /**
     * Pops a value from the stack.
     *
     * @return mixed|null
     */
    public function pop()
    {
        return array_pop($this->stack);
    }

    /**
     * Peeks at the top value of the stack without removing it.
     *
     * @return mixed|null
     */
    public function peek()
    {
        return end($this->stack);
    }

    /**
     * Clears the stack.
     *
     * @return void
     */
    public function clearStack(): void
    {
        $this->stack = [];
    }

    /**
     * Adds two numbers.
     *
     * @param float $num1
     * @param float $num2
     * @return float
     */
    public function add(float $num1, float $num2): float
    {
        return $num1 + $num2;
    }

    /**
     * Subtracts two numbers.
     *
     * @param float $num1
     * @param float $num2
     * @return float
     */
    public function subtract(float $num1, float $num2): float
    {
        return $num1 - $num2;
    }

    /**
     * Multiplies two numbers.
     *
     * @param float $num1
     * @param float $num2
     * @return float
     */
    public function multiply(float $num1, float $num2): float
    {
        return $num1 * $num2;
    }

    /**
     * Divides two numbers.
     *
     * @param float $num1
     * @param float $num2
     * @return float|string
     */
    public function divide(float $num1, float $num2)
    {
        if ($num2 == 0) {
            return "Cannot divide by zero";
        }
        return $num1 / $num2;
    }

    /**
     * Displays the result by popping it from the stack.
     *
     * @return mixed|null
     */
    public function displayResult()
    {
        return $this->pop();
    }
}