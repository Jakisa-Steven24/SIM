MIU PROCEDURAL PHP CRUD FORMS

This version is intentionally written in simple procedural PHP so the student can follow the code.
Every main form explicitly uses:
<form action="page.php" method="POST">

Database operations use procedural mysqli functions such as:
mysqli_connect()
mysqli_query()
mysqli_fetch_assoc()
mysqli_real_escape_string()

CRUD:
C = Create/Insert
R = Read/Select
U = Update
D = Delete

Delete also uses POST through a small form with action="the_same_page.php" method="POST".

IDs:
department.dept_id
lecturer.lecturer_id
course.course_id
course_unit.course_unit_id
student.student_id
enrollment.enrollment_id
registration.registration_id
payment.payment_id
fee_type.fee_type_id

INSTALL:
1. Put this folder in C:\xampp\htdocs\
2. Create/import the database named miu.
3. Make sure the table columns match the forms.
4. Start Apache and MySQL.
5. Open http://localhost/miu_procedural_crud/

Create data in this order:
Department -> Lecturer -> Course -> Course Unit -> Student -> Fee Type -> Enrollment/Registration/Payment
