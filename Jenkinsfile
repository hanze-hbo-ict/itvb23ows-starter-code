/* Requires the Docker Pipeline plugin */
pipeline {
    agent any 
    stages {
        stage('build') {
            steps {
                echo 'Hello World'
            }
        }
        stage('SonarQubeScanner'){
            steps{
                script {scannerHome = tool 'SonarQube Scanner'}
                withSonarQubeEnv('thomas'){
                    sh "${scannerHome}/bin/sonar-scanner"
                }
            }
        }
    }
}