<?php

/* Description
 * This is a random collection of modern and discrete math functions.
 */
class discrete {
  // 2D array.  Stores binomial coefficients.
  private $pascal_triangle;
  // Calculate a factorial (ex: n!).  Using memoization, to make this run a
  // little quicker.
  private $calculated_factorial;

  public function discrete(){
    $this->pascal_triangle[0][0] = 1;
    $this->pascal_triangle[1][0] = 1;
    $this->pascal_triangle[1][1] = 1;
    $this->calculated_factorial[0] = 1;
  }

  public function compute_binomial($x, $y, $n, $show_work = false){
    if($n < 0){
      trigger_error("The exponent cannot be less than 0");
      return 0;
    }
    $result = pow(($x + $y),$n);

    $this->calculate_pascal_triangle($n);

    if($show_work){
      echo "($x + $y)^$n = ";
      for($i=0;$i<=$n;$i++){
        $element = $this->pascal_triangle[$n][$i];
        $add = " ";
        if($i!=$n){
          $add = " + ";
          $element .= " ($x^".($n - $i).")";
        }
        if($i!=0){
          $element .= " ($y^".$i.")";
        }
        echo $element . $add;
      }
      echo " = " . $result . "<br><br>";
    }
    return $result;
  }

  /* I know that the binomial coefficient can be calculated using
   * (n!/(k!(n-k)!), but I wanted to show that I'm familiar with optimization
   * techniques, such as memoization.  Also, we only need to calculate approx.
   * half of the triangle, since the data is symmertrical.  Store the entire
   * triangle for user ease of use.
   */
  private function calculate_pascal_triangle($depth){
    $calculated_depth = count($this->pascal_triangle);
    if($calculated_depth > $depth){
      return;
    }
    $this->pascal_triangle[$calculated_depth][0] = 1;
    $half_triangle_width = ceil($calculated_depth/2) + 1;
    for($i=1;$i<$half_triangle_width;$i++){
      $this->pascal_triangle[$calculated_depth][$i] = $this->pascal_triangle[$calculated_depth - 1][$i-1] + $this->pascal_triangle[$calculated_depth - 1][$i];
      $this->pascal_triangle[$calculated_depth][$calculated_depth-$i] = $this->pascal_triangle[$calculated_depth][$i];
    }
    $this->pascal_triangle[$calculated_depth][$calculated_depth] = 1;
    $this->calculate_pascal_triangle($depth);
  }

  public function print_pascal_triangle($depth = 0){
    if($depth >= count($this->pascal_triangle)){
      return;
    }
    for($i=0;$i<=$depth;$i++){
      echo $this->pascal_triangle[$depth][$i] . " | ";
    }
    echo "<br>";
    $this->print_pascal_triangle($depth + 1);
  }

  public function get_poisson_distribution($k,$lambda){
    if($lambda < 1 || !is_numeric($lambda)){
      trigger_error("Lambda must be a positive, real number");
      return 0;
    }
    if($k < 1  || !is_numeric($k)){
      trigger_error("The number of occurances must be a positive, real number");
      return 0;
    }
    return (pow($lambda,$k) * pow(exp(1),($lambda * -1))/ $this->factorial($k));
  }

  public function factorial($factorial_number){
    if($factorial_number < 1 || !is_numeric($factorial_number)){
      trigger_error("Factorial number must be a positive, real number");
      return 0;
    }
    if($factorial_number <= 1){
      return 1;
    }
    if(isset($this->calculated_factorial[$factorial_number])){
      return $this->calculated_factorial[$factorial_number];
    }
    $this->calculated_factorial[$factorial_number] = $factorial_number * $this->factorial($factorial_number - 1);
    return $this->calculated_factorial[$factorial_number];
  }

  /* Calculate an unsigned lah number.  In other words, count the number of ways
   * a set of n elements can be partitioned into k nonempty linearly ordered
   * subsets
   */
  public function lah($n,$k){
    if($n==1 && $k==1) return 1;
    if(empty($this->pascal_triangle[$n][$k])){
      $depth_to_calculate = $n > $k ? $n : $k;
      $this->calculate_pascal_triangle($depth_to_calculate);
    }
    return pow(-1,$n) * $this->pascal_triangle[$n-1][$k-1] * ($this->factorial($n)/$this->factorial($k));
  }
}

$test = new discrete();
$test->compute_binomial(2,3,4,true);

echo "Current calculated pascal triangle<br>";
$test->print_pascal_triangle();

echo "<br><br>";
$test->compute_binomial(3,7,9,true);

echo "New calculated pascal triangle<br>";
$test->print_pascal_triangle();

echo "<br>5! = " . $test->factorial(5);
echo "<br><br>Poisson Distribution<br> f(5,3) = " . $test->get_poisson_distribution(5, 3);

echo "<br><br>Unsigned Lah: L'(4,3) = ".$test->lah(4, 3)."<br>Current Pascal Triangle<br>";
$test->print_pascal_triangle();
echo "<br><br>Unsigned Lah: L'(5,4) = ".$test->lah(5, 4)."<br>Current Pascal Triangle<br>";
$test->print_pascal_triangle();
echo "<br><br>Unsigned Lah: L'(11,7) = ".$test->lah(11, 7)."<br>Current Pascal Triangle<br>";
$test->print_pascal_triangle();


