import java.util.Scanner;

public class UserPrompt {
    public static void main(String[] args) {
        Scanner scanner = new Scanner(System.in);

        // Prompt the user for their name
        System.out.print("Enter your name: ");
        String name = scanner.nextLine();

        // Prompt the user for their age
        System.out.print("Enter your age: ");
        int age = scanner.nextInt();
        scanner.nextLine(); // Consume newline

        // Prompt the user for their favorite color
        System.out.print("Enter your favorite color: ");
        String color = scanner.nextLine();

        // Output a personalized message
        System.out.println("Hello " + name + "! At " + age + " years old, it's great that your favorite color is " + color + ".");
        
        scanner.close();
    }
}
