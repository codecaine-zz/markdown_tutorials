# Data Structures & Algorithmic Patterns Implementation Guide

While Big-O notation establishes theoretical time and space bounds, writing production systems and passing technical interviews requires recognizing canonical algorithmic patterns. This guide catalogs essential data structures and core design patterns with clean implementations.

---

## 📚 Table of Contents

1. [Data Structure Operations & Complexity Matrix](#1-data-structure-operations--complexity-matrix)
2. [Pattern 1: Two Pointers & Fast/Slow Pointer](#2-pattern-1-two-pointers--fastslow-pointer)
3. [Pattern 2: Dynamic & Fixed Sliding Window](#3-pattern-2-dynamic--fixed-sliding-window)
4. [Pattern 3: Monotonic Stack & Queue](#4-pattern-3-monotonic-stack--queue)
5. [Pattern 4: Binary Search & Search Space Bisection](#5-pattern-4-binary-search--search-space-bisection)
6. [Pattern 5: Tree Traversals (DFS vs. BFS)](#6-pattern-5-tree-traversals-dfs-vs-bfs)
7. [Pattern 6: Graph Algorithms (Topological Sort & Dijkstra)](#7-pattern-6-graph-algorithms-topological-sort--dijkstra)
8. [Pattern 7: Dynamic Programming (0/1 Knapsack Pattern)](#8-pattern-7-dynamic-programming-01-knapsack-pattern)

---

## 1. Data Structure Operations & Complexity Matrix

| Data Structure | Access | Search | Insertion | Deletion | Space |
| :--- | :--- | :--- | :--- | :--- | :--- |
| **Array** | $O(1)$ | $O(N)$ | $O(N)$ | $O(N)$ | $O(N)$ |
| **Hash Table** | N/A | $O(1)$ avg | $O(1)$ avg | $O(1)$ avg | $O(N)$ |
| **Singly Linked List** | $O(N)$ | $O(N)$ | $O(1)$ (head) | $O(1)$ (head) | $O(N)$ |
| **Binary Search Tree** | $O(\log N)$ | $O(\log N)$ | $O(\log N)$ | $O(\log N)$ | $O(N)$ |
| **Min/Max Binary Heap** | $O(1)$ (peek) | $O(N)$ | $O(\log N)$ | $O(\log N)$ (pop) | $O(N)$ |
| **Trie (Prefix Tree)** | $O(L)$ | $O(L)$ | $O(L)$ | $O(L)$ | $O(\Sigma \cdot L)$ |

*(Where $N$ is number of elements, $L$ is key length, and $\Sigma$ is alphabet size)*

---

## 2. Pattern 1: Two Pointers & Fast/Slow Pointer

### Two Pointers (Two-Sum on Sorted Array)

```python
def two_sum_sorted(numbers: list[int], target: int) -> tuple[int, int] | None:
    left, right = 0, len(numbers) - 1
    
    while left < right:
        current_sum = numbers[left] + numbers[right]
        if current_sum == target:
            return (left, right)
        elif current_sum < target:
            left += 1   # Increase sum
        else:
            right -= 1  # Decrease sum
            
    return None
```

### Fast & Slow Pointers (Floyd's Cycle Detection)

```python
class ListNode:
    def __init__(self, val=0, next=None):
        self.val = val
        self.next = next

def has_cycle(head: ListNode | None) -> bool:
    slow, fast = head, head
    while fast and fast.next:
        slow = slow.next
        fast = fast.next.next
        if slow == fast:
            return True
    return False
```

---

## 3. Pattern 2: Dynamic & Fixed Sliding Window

### Longest Substring Without Repeating Characters

```python
def length_of_longest_substring(s: str) -> int:
    char_index_map = {}
    max_length = 0
    start = 0

    for end, char in enumerate(s):
        if char in char_index_map and char_index_map[char] >= start:
            start = char_index_map[char] + 1
        
        char_index_map[char] = end
        max_length = max(max_length, end - start + 1)

    return max_length
```

---

## 4. Pattern 3: Monotonic Stack & Queue

Monotonic stacks maintain elements in strictly ascending or descending order to solve "Next Greater/Smaller Element" problems in $O(N)$ time instead of nested $O(N^2)$ loops.

```python
def next_greater_elements(nums: list[int]) -> list[int]:
    result = [-1] * len(nums)
    stack = []  # Stores indices

    for i, val in enumerate(nums):
        while stack and nums[stack[-1]] < val:
            prev_index = stack.pop()
            result[prev_index] = val
        stack.append(i)

    return result
```

---

## 5. Pattern 4: Binary Search & Search Space Bisection

Beyond finding values in sorted arrays, binary search can optimize numerical monotonic functions.

```python
def binary_search(nums: list[int], target: int) -> int:
    low, high = 0, len(nums) - 1
    
    while low <= high:
        # Prevent integer overflow in languages with fixed integer sizes
        mid = low + (high - low) // 2
        
        if nums[mid] == target:
            return mid
        elif nums[mid] < target:
            low = mid + 1
        else:
            high = mid - 1
            
    return -1
```

---

## 6. Pattern 5: Tree Traversals (DFS vs. BFS)

```python
from collections import deque

class TreeNode:
    def __init__(self, val=0, left=None, right=None):
        self.val = val
        self.left = left
        self.right = right

# Depth-First Search (DFS Inorder: Left -> Root -> Right)
def inorder_traversal(root: TreeNode | None) -> list[int]:
    result = []
    def traverse(node):
        if not node:
            return
        traverse(node.left)
        result.append(node.val)
        traverse(node.right)
    traverse(root)
    return result

# Breadth-First Search (BFS Level-Order)
def level_order(root: TreeNode | None) -> list[list[int]]:
    if not root:
        return []
    
    levels = []
    queue = deque([root])
    
    while queue:
        level_size = len(queue)
        current_level = []
        for _ in range(level_size):
            node = queue.popleft()
            current_level.append(node.val)
            if node.left:
                queue.append(node.left)
            if node.right:
                queue.append(node.right)
        levels.append(current_level)
        
    return levels
```

---

## 7. Pattern 6: Graph Algorithms

### Topological Sort (Kahn's Algorithm via In-Degree BFS)

Indispensable for task scheduling, compiler dependency resolution, and build pipelines:

```python
from collections import deque, defaultdict

def topological_sort(num_nodes: int, edges: list[tuple[int, int]]) -> list[int]:
    adj = defaultdict(list)
    in_degree = [0] * num_nodes

    for src, dst in edges:
        adj[src].append(dst)
        in_degree[dst] += 1

    queue = deque([i for i in range(num_nodes) if in_degree[i] == 0])
    order = []

    while queue:
        node = queue.popleft()
        order.append(node)
        for neighbor in adj[node]:
            in_degree[neighbor] -= 1
            if in_degree[neighbor] == 0:
                queue.append(neighbor)

    if len(order) != num_nodes:
        raise ValueError("Graph contains a cycle; topological sort impossible!")
        
    return order
```

---

## 8. Pattern 7: Dynamic Programming (0/1 Knapsack Pattern)

Dynamic programming resolves overlapping subproblems by storing calculated states.

```python
def knapsack_01(weights: list[int], values: list[int], capacity: int) -> int:
    n = len(weights)
    dp = [0] * (capacity + 1)

    for i in range(n):
        w, v = weights[i], values[i]
        # Iterate backwards to preserve values from previous item iteration
        for cap in range(capacity, w - 1, -1):
            dp[cap] = max(dp[cap], dp[cap - w] + v)

    return dp[capacity]
```
