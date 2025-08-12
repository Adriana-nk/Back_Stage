import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Observable, throwError } from 'rxjs';
import { catchError, map } from 'rxjs/operators';

// Interfaces pour typer les réponses
export interface LoginResponse {
  success: boolean;
  token: string;
  user: {
    id: number;
    name: string;
    email: string;
    email_verified_at: string | null;
    created_at: string;
    updated_at: string;
  };
}

export interface RegisterResponse {
  success: boolean;
  message: string;
  user: {
    id: number;
    name: string;
    email: string;
    created_at: string;
    updated_at: string;
  };
}

export interface ForgotPasswordResponse {
  success: boolean;
  message: string;
}

export interface ResetPasswordResponse {
  success: boolean;
  message: string;
}

export interface ValidateAccountResponse {
  success: boolean;
  message: string;
}

export interface ErrorResponse {
  message: string;
  errors?: { [key: string]: string[] };
}

@Injectable({
  providedIn: 'root'
})
export class AuthService {
  private apiUrl = 'http://127.0.0.1:8000/api/v1';
  private tokenKey = 'auth_token';

  constructor(private http: HttpClient) {}

  /**
   * Enregistrement d'un nouvel utilisateur
   */
  register(userData: {
    name: string;
    email: string;
    password: string;
    password_confirmation: string;
  }): Observable<RegisterResponse> {
    return this.http.post<RegisterResponse>(`${this.apiUrl}/register`, userData)
      .pipe(
        catchError(this.handleError)
      );
  }

  /**
   * Connexion d'un utilisateur
   */
  login(credentials: {
    email: string;
    password: string;
  }): Observable<LoginResponse> {
    return this.http.post<LoginResponse>(`${this.apiUrl}/login`, credentials)
      .pipe(
        map(response => {
          if (response.token) {
            this.setToken(response.token);
          }
          return response;
        }),
        catchError(this.handleError)
      );
  }

  /**
   * Demande de réinitialisation de mot de passe
   */
  forgotPassword(email: string): Observable<ForgotPasswordResponse> {
    return this.http.post<ForgotPasswordResponse>(`${this.apiUrl}/forgot-password`, { email })
      .pipe(
        catchError(this.handleError)
      );
  }

  /**
   * Réinitialisation du mot de passe avec le token
   */
  resetPassword(data: {
    email: string;
    token: string;
    password: string;
    password_confirmation: string;
  }): Observable<ResetPasswordResponse> {
    return this.http.post<ResetPasswordResponse>(`${this.apiUrl}/reset-password`, data)
      .pipe(
        catchError(this.handleError)
      );
  }

  /**
   * Validation du compte utilisateur
   */
  validateAccount(data: {
    email: string;
    validation_code: string;
  }): Observable<ValidateAccountResponse> {
    return this.http.post<ValidateAccountResponse>(`${this.apiUrl}/validate-account`, data)
      .pipe(
        catchError(this.handleError)
      );
  }

  /**
   * Déconnexion de l'utilisateur
   */
  logout(): Observable<{ message: string }> {
    const headers = this.getAuthHeaders();
    return this.http.post<{ message: string }>(`${this.apiUrl}/logout`, {}, { headers })
      .pipe(
        map(response => {
          this.removeToken();
          return response;
        }),
        catchError(this.handleError)
      );
  }

  /**
   * Récupération des informations de l'utilisateur connecté
   */
  getUser(): Observable<any> {
    const headers = this.getAuthHeaders();
    return this.http.get(`${this.apiUrl}/user`, { headers })
      .pipe(
        catchError(this.handleError)
      );
  }

  /**
   * Rafraîchissement du token d'authentification
   */
  refreshToken(): Observable<{ token: string }> {
    const headers = this.getAuthHeaders();
    return this.http.post<{ token: string }>(`${this.apiUrl}/refresh`, {}, { headers })
      .pipe(
        map(response => {
          this.setToken(response.token);
          return response;
        }),
        catchError(this.handleError)
      );
  }

  /**
   * Vérifier si l'utilisateur est connecté
   */
  isLoggedIn(): boolean {
    return !!this.getToken();
  }

  /**
   * Récupérer le token stocké
   */
  getToken(): string | null {
    return localStorage.getItem(this.tokenKey);
  }

  /**
   * Stocker le token
   */
  private setToken(token: string): void {
    localStorage.setItem(this.tokenKey, token);
  }

  /**
   * Supprimer le token
   */
  private removeToken(): void {
    localStorage.removeItem(this.tokenKey);
  }

  /**
   * Obtenir les headers d'authentification
   */
  private getAuthHeaders(): HttpHeaders {
    const token = this.getToken();
    return new HttpHeaders({
      'Content-Type': 'application/json',
      'Accept': 'application/json',
      ...(token && { 'Authorization': `Bearer ${token}` })
    });
  }

  /**
   * Gestion des erreurs
   */
  private handleError(error: any): Observable<never> {
    let errorMessage = 'Une erreur est survenue';
    
    if (error.error instanceof ErrorEvent) {
      // Erreur côté client
      errorMessage = error.error.message;
    } else {
      // Erreur côté serveur
      if (error.status === 0) {
        errorMessage = 'Impossible de se connecter au serveur';
      } else if (error.status === 422) {
        // Erreurs de validation Laravel
        const errors = error.error?.errors || {};
        const firstError = Object.values(errors)[0] as string[];
        errorMessage = firstError?.[0] || 'Données invalides';
      } else if (error.status === 401) {
        errorMessage = 'Identifiants incorrects';
      } else if (error.status === 404) {
        errorMessage = 'Endpoint non trouvé';
      } else {
        errorMessage = error.error?.message || `Erreur ${error.status}`;
      }
    }
    
    console.error('Erreur AuthService:', errorMessage);
    return throwError(() => ({
      message: errorMessage,
      status: error.status,
      errors: error.error?.errors || {}
    }));
  }
}
