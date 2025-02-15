require('dotenv').config();
const express = require('express');
const axios = require('axios');
const multer = require('multer');
const basicAuth = require('basic-auth');

const app = express();
const PORT = 3000;

app.use(express.json());


const getAuthHeader = () => {
    const username = process.env.USERNAME;
    const password = process.env.PASSWORD;
    return 'Basic ' + Buffer.from(`${username}:${password}`).toString('base64');
};


const NEXTGIS_URL = process.env.NEXTGIS_URL;
const LAYER_ID = process.env.LAYER_ID;


app.post('/addFeature', async (req, res) => {
    try {
        const { fields, geom } = req.body;

        const response = await axios.post(
            `${NEXTGIS_URL}/api/resource/${LAYER_ID}/feature/`,
            { fields, geom },
            { headers: { Authorization: getAuthHeader() } }
        );

        res.json(response.data);
    } catch (error) {
        res.status(500).json({ error: error.response?.data || error.message });
    }
});


app.get('/getFeatures', async (req, res) => {
    try {
        const response = await axios.get(
            `${NEXTGIS_URL}/api/resource/${LAYER_ID}/feature/`,
            { headers: { Authorization: getAuthHeader() } }
        );

        res.json(response.data);
    } catch (error) {
        res.status(500).json({ error: error.response?.data || error.message });
    }
});


app.delete('/deleteFeature', async (req, res) => {
    try {
        const { ids } = req.body;

        const response = await axios({
            method: 'delete',
            url: `${NEXTGIS_URL}/api/resource/${LAYER_ID}/feature/`,
            headers: { Authorization: getAuthHeader() },
            data: ids
        });

        res.json(response.data);
    } catch (error) {
        res.status(500).json({ error: error.response?.data || error.message });
    }
});


const upload = multer({ storage: multer.memoryStorage() });

app.post('/uploadFile', upload.single('file'), async (req, res) => {
    try {
        const formData = new FormData();
        formData.append('file', req.file.buffer, req.file.originalname);
        formData.append('name', req.file.originalname);

        const response = await axios.post(
            `${NEXTGIS_URL}/api/component/file_upload/`,
            formData,
            { headers: { Authorization: getAuthHeader(), ...formData.getHeaders() } }
        );

        res.json(response.data);
    } catch (error) {
        res.status(500).json({ error: error.response?.data || error.message });
    }
});



app.post('/attachFile', async (req, res) => {
    try {
        const { featureId, fileId, fileName, fileSize, mimeType } = req.body;

        const response = await axios.post(
            `${NEXTGIS_URL}/api/resource/${LAYER_ID}/feature/${featureId}/attachment/`,
            {
                name: fileName,
                size: fileSize,
                mime_type: mimeType,
                file_upload: { id: fileId, size: fileSize },
            },
            { headers: { Authorization: getAuthHeader() } }
        );

        res.json(response.data);
    } catch (error) {
        res.status(500).json({ error: error.response?.data || error.message });
    }
});

app.listen(PORT, () => {
    console.log(`Server running on http://localhost:${PORT}`);
});


app.get('/', (req, res) => {
    res.send('Сервер работает! 🚀');
});

const cors = require('cors');
app.use(cors());