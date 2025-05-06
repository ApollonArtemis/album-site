// 🔧 Replace this with your Firebase config
const firebaseConfig = {
    apiKey: "AIzaSyBrF9-HakZtv_WKniaiQoQFnPBz1tz3i_Q",
    authDomain: "photoalbum-66652.firebaseapp.com",
    projectId: "photoalbum-66652",
    storageBucket: "photoalbum-66652.firebasestorage.app",
    messagingSenderId: "376093581597",
    appId: "1:376093581597:web:490df3309b7bcb799e41f4"
};

firebase.initializeApp(firebaseConfig);
const db = firebase.firestore();
const storage = firebase.storage();

async function createFolder() {
    const folderName = document.getElementById('folderName').value.trim();
    if (!folderName) return alert("Enter a folder name");

    await db.collection('folders').doc(folderName).set({ created: new Date() });
    loadFolders();
}

async function loadFolders() {
    const folderSelect = document.getElementById('folderSelect');
    folderSelect.innerHTML = '';
    const snapshot = await db.collection('folders').get();

    snapshot.forEach(doc => {
        const option = document.createElement('option');
        option.value = doc.id;
        option.textContent = doc.id;
        folderSelect.appendChild(option);
    });

    if (snapshot.docs.length > 0) {
        loadImages(snapshot.docs[0].id);
    }

    folderSelect.onchange = () => loadImages(folderSelect.value);
}

async function uploadImage() {
    const file = document.getElementById('imageInput').files[0];
    const folder = document.getElementById('folderSelect').value;
    if (!file || !folder) return alert("Choose folder and image");

    const storageRef = storage.ref(`images/${folder}/${file.name}`);
    await storageRef.put(file);
    const url = await storageRef.getDownloadURL();

    await db.collection('folders').doc(folder).collection('images').add({
        name: file.name,
        url: url,
        uploaded: new Date()
    });

    loadImages(folder);
}

async function loadImages(folder) {
    const gallery = document.getElementById('gallery');
    gallery.innerHTML = '';

    const snapshot = await db.collection('folders').doc(folder).collection('images').get();
    snapshot.forEach(doc => {
        const img = document.createElement('img');
        img.src = doc.data().url;
        img.className = 'col-md-3 m-2 rounded';
        img.style.height = '150px';
        gallery.appendChild(img);
    });
}

loadFolders();
