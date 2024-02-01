pipeline {
    agent { docker { image 'php:8.3.0-alpine3.18' } }
    stages {
        stage('build') {
            steps {
                sh 'php --version'
            }
        }
        stage('SonarQube') {
            steps {
                script { scannerHome = tool 'SonarQube_Scanner' }
                withSonarQubeEnv('SonarQube') {
                    sh "${scannerHome}/bin/sonar-scanner -Dsonar.projectKey=ows"
                }
            }
        }
    }
}