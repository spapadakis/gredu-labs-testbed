# Ενεργοποίηση σύνδεσης μέσω βάσης δεδομένων

Το συγκεκριμένο module ενεργοποιεί τη φόρμα σύνδεσης μέσω βάση δεδομένων
άσχετα από τη ρύθμιση Container['settings']['sso']['enable_database_login'].
Χρειάζεται να ενεργοποιηθεί μόνο στην περίπτωση που έχει ενεργοποιηθεί το
module sch_sso και απαιτείται σύνδεση τοπικού χρήστη για συγκεκριμένες 
λειτουργίες. 

## Ρυθμίσεις 

Το αρχείο `enable_db_login.{global,local}.php χρησιμοποιείται για την 
παραμετροποίηση. Οι προεπιλεγμένες ρυθμίσεις είναι οι παρακάτω.
```
    'enabledblogin' => [
        'enable_routes' => [
            'enabledblogin'
        ],
        'enable_patterns' => [
            '/enabledblogin'
        ],
        'disable_routes' => [
            'disabledblogin',
            'user.logout'
        ],
        'disable_patterns' => [
            '/disabledblogin',
            '/user/logout'
        ],
    ],
```

Οι ρυθμίσεις έχουν ως εξής:

- enable_routes: ονόματα routes που ενεργοποιούν τη σύνδεση μέσω ΒΔ
- enable_patterns: url paths που ενεργοποιούν τη σύνδεση μέσω ΒΔ
- disable_routes: ονόματα routes που απενεργοποιούν τη σύνδεση μέσω ΒΔ
- disable_patterns: url paths που απενεργοποιούν τη σύνδεση μέσω ΒΔ

_Προτεραιότητα έχει η απενεργοποίηση._

##Routes

Το module δηλώνει δύο routes (στα αντίστοιχα paths) ώστε να παρέχεται ένα
παράδειγμα χρήσης. 

- enabledblogin (/enabledblogin) που ενεργοποιεί τη σύνδεση μέσω ΒΔ
- disabledblogin (/disabledblogin) που απενεργοποιεί τη σύνδεση μέσω ΒΔ

*Δεν* είναι απαραίτητο να προστεθούν τα routes στο `acl.{global,local}.php` 
(acl.guards.routes).

