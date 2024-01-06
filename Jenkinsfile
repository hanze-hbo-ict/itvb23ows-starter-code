pipeline {
    agent { docker { image 'php:8.2-apache' } }
    stages {
        stage('build') {
            steps {
                sh 'php --version'
            }
        }
    }
}