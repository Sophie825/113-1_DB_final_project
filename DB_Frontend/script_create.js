document.addEventListener('DOMContentLoaded', () => {
    // 選取彈出視窗元素
    const createModal = document.getElementById('createModal');
    const editModal = document.getElementById('editModal');
    const closeCreateModal = document.getElementById('closeCreateModal');
    const closeEditModal = document.getElementById('closeEditModal');

    // 新增按鈕的功能
    document.getElementById('createButton').addEventListener('click', () => {
        createModal.style.display = 'flex';
    });

    // 關閉新增視窗
    closeCreateModal.addEventListener('click', () => {
        createModal.style.display = 'none';
    });

    // 關閉編輯視窗
    closeEditModal.addEventListener('click', () => {
        editModal.style.display = 'none';
    });

    // 點擊編輯按鈕的功能 (事件委派)
    document.querySelector('table').addEventListener('click', (event) => {
        if (event.target.classList.contains('edit-button')) {
            const studentId = event.target.dataset.id; // 獲取學生 ID
            const studentData = getStudentDataById(studentId); // 獲取學生資料
    
            // 填充編輯表單資料
            document.getElementById('editStudentForm').elements['name'].value = studentData.name;
            document.getElementById('editStudentForm').elements['school'].value = studentData.school;
            document.getElementById('editStudentForm').elements['grade'].value = studentData.grade;
            document.getElementById('editStudentForm').elements['tel'].value = studentData.tel;
            document.getElementById('editStudentForm').elements['address'].value = studentData.address;
            document.getElementById('editStudentForm').elements['parent'].value = studentData.parent;
            document.getElementById('editStudentForm').elements['parentTel'].value = studentData.parentTel;
    
            // 多選班級處理
            const classOptions = document.getElementById('editStudentForm').elements['class'].options;
            Array.from(classOptions).forEach(option => {
                option.selected = studentData.class.includes(option.value);
            });
    
            // 顯示編輯彈出視窗
            document.getElementById('editModal').style.display = 'flex';
        }
    });
    
    // 點擊視窗外部關閉彈出視窗
    window.addEventListener('click', (event) => {
        if (event.target === createModal) {
            createModal.style.display = 'none';
        } else if (event.target === editModal) {
            editModal.style.display = 'none';
        }
    });
});
