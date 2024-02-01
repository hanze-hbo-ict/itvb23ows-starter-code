pipeline {
    agent any
    stages {
        stage('SonarQube') {
            steps {
                script { scannerHome = tool 'SonarQubeScanner' }
                withSonarQubeEnv('SonarQube') {
                    sh "${scannerHome}/bin/./sonar-scanner -Dsonar.projectKey=ows"
                }
            }
        }
    }
}