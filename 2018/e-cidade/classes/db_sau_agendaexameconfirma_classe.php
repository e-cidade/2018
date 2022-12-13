<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
 *                            www.dbseller.com.br                     
 *                         e-cidade@dbseller.com.br                   
 *                                                                    
 *  Este programa e software livre; voce pode redistribui-lo e/ou     
 *  modifica-lo sob os termos da Licenca Publica Geral GNU, conforme  
 *  publicada pela Free Software Foundation; tanto a versao 2 da      
 *  Licenca como (a seu criterio) qualquer versao mais nova.          
 *                                                                    
 *  Este programa e distribuido na expectativa de ser util, mas SEM   
 *  QUALQUER GARANTIA; sem mesmo a garantia implicita de              
 *  COMERCIALIZACAO ou de ADEQUACAO A QUALQUER PROPOSITO EM           
 *  PARTICULAR. Consulte a Licenca Publica Geral GNU para obter mais  
 *  detalhes.                                                         
 *                                                                    
 *  Voce deve ter recebido uma copia da Licenca Publica Geral GNU     
 *  junto com este programa; se nao, escreva para a Free Software     
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA          
 *  02111-1307, USA.                                                  
 *  
 *  Copia da licenca no diretorio licenca/licenca_en.txt 
 *                                licenca/licenca_pt.txt 
 */

//MODULO: Ambulatorial
//CLASSE DA ENTIDADE sau_agendaexameconfirma
class cl_sau_agendaexameconfirma { 
   // cria variaveis de erro 
   var $rotulo     = null; 
   var $query_sql  = null; 
   var $numrows    = 0; 
   var $numrows_incluir = 0; 
   var $numrows_alterar = 0; 
   var $numrows_excluir = 0; 
   var $erro_status= null; 
   var $erro_sql   = null; 
   var $erro_banco = null;  
   var $erro_msg   = null;  
   var $erro_campo = null;  
   var $pagina_retorno = null; 
   // cria variaveis do arquivo 
   var $s133_i_codigo = 0; 
   var $s133_i_agendaexames = 0; 
   var $s133_i_login = 0; 
   var $s133_d_data_dia = null; 
   var $s133_d_data_mes = null; 
   var $s133_d_data_ano = null; 
   var $s133_d_data = null; 
   var $s133_c_hora = null; 
   var $s133_c_protocolo = null; 
   var $s133_c_observacoes = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 s133_i_codigo = int4 = Código Sequencial 
                 s133_i_agendaexames = int4 = Código do Exame 
                 s133_i_login = int4 = Cod. Usuário 
                 s133_d_data = date = Data da Confirmação 
                 s133_c_hora = varchar(5) = Hora 
                 s133_c_protocolo = varchar(10) = Protocolo 
                 s133_c_observacoes = text = Observações 
                 ";
   //funcao construtor da classe 
   function cl_sau_agendaexameconfirma() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("sau_agendaexameconfirma"); 
     $this->pagina_retorno =  basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"]);
   }
   //funcao erro 
   function erro($mostra,$retorna) { 
     if(($this->erro_status == "0") || ($mostra == true && $this->erro_status != null )){
        echo "<script>alert(\"".$this->erro_msg."\");</script>";
        if($retorna==true){
           echo "<script>location.href='".$this->pagina_retorno."'</script>";
        }
     }
   }
   // funcao para atualizar campos
   function atualizacampos($exclusao=false) {
     if($exclusao==false){
       $this->s133_i_codigo = ($this->s133_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["s133_i_codigo"]:$this->s133_i_codigo);
       $this->s133_i_agendaexames = ($this->s133_i_agendaexames == ""?@$GLOBALS["HTTP_POST_VARS"]["s133_i_agendaexames"]:$this->s133_i_agendaexames);
       $this->s133_i_login = ($this->s133_i_login == ""?@$GLOBALS["HTTP_POST_VARS"]["s133_i_login"]:$this->s133_i_login);
       if($this->s133_d_data == ""){
         $this->s133_d_data_dia = ($this->s133_d_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["s133_d_data_dia"]:$this->s133_d_data_dia);
         $this->s133_d_data_mes = ($this->s133_d_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["s133_d_data_mes"]:$this->s133_d_data_mes);
         $this->s133_d_data_ano = ($this->s133_d_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["s133_d_data_ano"]:$this->s133_d_data_ano);
         if($this->s133_d_data_dia != ""){
            $this->s133_d_data = $this->s133_d_data_ano."-".$this->s133_d_data_mes."-".$this->s133_d_data_dia;
         }
       }
       $this->s133_c_hora = ($this->s133_c_hora == ""?@$GLOBALS["HTTP_POST_VARS"]["s133_c_hora"]:$this->s133_c_hora);
       $this->s133_c_protocolo = ($this->s133_c_protocolo == ""?@$GLOBALS["HTTP_POST_VARS"]["s133_c_protocolo"]:$this->s133_c_protocolo);
       $this->s133_c_observacoes = ($this->s133_c_observacoes == ""?@$GLOBALS["HTTP_POST_VARS"]["s133_c_observacoes"]:$this->s133_c_observacoes);
     }else{
       $this->s133_i_codigo = ($this->s133_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["s133_i_codigo"]:$this->s133_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($s133_i_codigo){ 
      $this->atualizacampos();
     if($this->s133_i_agendaexames == null ){ 
       $this->erro_sql = " Campo Código do Exame nao Informado.";
       $this->erro_campo = "s133_i_agendaexames";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->s133_i_login == null ){ 
       $this->erro_sql = " Campo Cod. Usuário nao Informado.";
       $this->erro_campo = "s133_i_login";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->s133_d_data == null ){ 
       $this->erro_sql = " Campo Data da Confirmação nao Informado.";
       $this->erro_campo = "s133_d_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->s133_c_hora == null ){ 
       $this->erro_sql = " Campo Hora nao Informado.";
       $this->erro_campo = "s133_c_hora";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->s133_c_protocolo == null ){ 
       $this->erro_sql = " Campo Protocolo nao Informado.";
       $this->erro_campo = "s133_c_protocolo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->s133_c_observacoes == null ){ 
       $this->erro_sql = " Campo Observações nao Informado.";
       $this->erro_campo = "s133_c_observacoes";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($s133_i_codigo == "" || $s133_i_codigo == null ){
       $result = db_query("select nextval('sau_agendaexameconfirma_s133_i_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: sau_agendaexameconfirma_s133_i_codigo_seq do campo: s133_i_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->s133_i_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from sau_agendaexameconfirma_s133_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $s133_i_codigo)){
         $this->erro_sql = " Campo s133_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->s133_i_codigo = $s133_i_codigo; 
       }
     }
     if(($this->s133_i_codigo == null) || ($this->s133_i_codigo == "") ){ 
       $this->erro_sql = " Campo s133_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into sau_agendaexameconfirma(
                                       s133_i_codigo 
                                      ,s133_i_agendaexames 
                                      ,s133_i_login 
                                      ,s133_d_data 
                                      ,s133_c_hora 
                                      ,s133_c_protocolo 
                                      ,s133_c_observacoes 
                       )
                values (
                                $this->s133_i_codigo 
                               ,$this->s133_i_agendaexames 
                               ,$this->s133_i_login 
                               ,".($this->s133_d_data == "null" || $this->s133_d_data == ""?"null":"'".$this->s133_d_data."'")." 
                               ,'$this->s133_c_hora' 
                               ,'$this->s133_c_protocolo' 
                               ,'$this->s133_c_observacoes' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Confirmacao da realizacao do exame ($this->s133_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Confirmacao da realizacao do exame já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Confirmacao da realizacao do exame ($this->s133_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->s133_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->s133_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,14328,'$this->s133_i_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,2521,14328,'','".AddSlashes(pg_result($resaco,0,'s133_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2521,14329,'','".AddSlashes(pg_result($resaco,0,'s133_i_agendaexames'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2521,14330,'','".AddSlashes(pg_result($resaco,0,'s133_i_login'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2521,14331,'','".AddSlashes(pg_result($resaco,0,'s133_d_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2521,14332,'','".AddSlashes(pg_result($resaco,0,'s133_c_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2521,14333,'','".AddSlashes(pg_result($resaco,0,'s133_c_protocolo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2521,14334,'','".AddSlashes(pg_result($resaco,0,'s133_c_observacoes'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($s133_i_codigo=null) { 
      $this->atualizacampos();
     $sql = " update sau_agendaexameconfirma set ";
     $virgula = "";
     if(trim($this->s133_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s133_i_codigo"])){ 
       $sql  .= $virgula." s133_i_codigo = $this->s133_i_codigo ";
       $virgula = ",";
       if(trim($this->s133_i_codigo) == null ){ 
         $this->erro_sql = " Campo Código Sequencial nao Informado.";
         $this->erro_campo = "s133_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->s133_i_agendaexames)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s133_i_agendaexames"])){ 
       $sql  .= $virgula." s133_i_agendaexames = $this->s133_i_agendaexames ";
       $virgula = ",";
       if(trim($this->s133_i_agendaexames) == null ){ 
         $this->erro_sql = " Campo Código do Exame nao Informado.";
         $this->erro_campo = "s133_i_agendaexames";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->s133_i_login)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s133_i_login"])){ 
       $sql  .= $virgula." s133_i_login = $this->s133_i_login ";
       $virgula = ",";
       if(trim($this->s133_i_login) == null ){ 
         $this->erro_sql = " Campo Cod. Usuário nao Informado.";
         $this->erro_campo = "s133_i_login";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->s133_d_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s133_d_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["s133_d_data_dia"] !="") ){ 
       $sql  .= $virgula." s133_d_data = '$this->s133_d_data' ";
       $virgula = ",";
       if(trim($this->s133_d_data) == null ){ 
         $this->erro_sql = " Campo Data da Confirmação nao Informado.";
         $this->erro_campo = "s133_d_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["s133_d_data_dia"])){ 
         $sql  .= $virgula." s133_d_data = null ";
         $virgula = ",";
         if(trim($this->s133_d_data) == null ){ 
           $this->erro_sql = " Campo Data da Confirmação nao Informado.";
           $this->erro_campo = "s133_d_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->s133_c_hora)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s133_c_hora"])){ 
       $sql  .= $virgula." s133_c_hora = '$this->s133_c_hora' ";
       $virgula = ",";
       if(trim($this->s133_c_hora) == null ){ 
         $this->erro_sql = " Campo Hora nao Informado.";
         $this->erro_campo = "s133_c_hora";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->s133_c_protocolo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s133_c_protocolo"])){ 
       $sql  .= $virgula." s133_c_protocolo = '$this->s133_c_protocolo' ";
       $virgula = ",";
       if(trim($this->s133_c_protocolo) == null ){ 
         $this->erro_sql = " Campo Protocolo nao Informado.";
         $this->erro_campo = "s133_c_protocolo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->s133_c_observacoes)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s133_c_observacoes"])){ 
       $sql  .= $virgula." s133_c_observacoes = '$this->s133_c_observacoes' ";
       $virgula = ",";
       if(trim($this->s133_c_observacoes) == null ){ 
         $this->erro_sql = " Campo Observações nao Informado.";
         $this->erro_campo = "s133_c_observacoes";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($s133_i_codigo!=null){
       $sql .= " s133_i_codigo = $this->s133_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->s133_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,14328,'$this->s133_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["s133_i_codigo"]) || $this->s133_i_codigo != "")
           $resac = db_query("insert into db_acount values($acount,2521,14328,'".AddSlashes(pg_result($resaco,$conresaco,'s133_i_codigo'))."','$this->s133_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["s133_i_agendaexames"]) || $this->s133_i_agendaexames != "")
           $resac = db_query("insert into db_acount values($acount,2521,14329,'".AddSlashes(pg_result($resaco,$conresaco,'s133_i_agendaexames'))."','$this->s133_i_agendaexames',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["s133_i_login"]) || $this->s133_i_login != "")
           $resac = db_query("insert into db_acount values($acount,2521,14330,'".AddSlashes(pg_result($resaco,$conresaco,'s133_i_login'))."','$this->s133_i_login',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["s133_d_data"]) || $this->s133_d_data != "")
           $resac = db_query("insert into db_acount values($acount,2521,14331,'".AddSlashes(pg_result($resaco,$conresaco,'s133_d_data'))."','$this->s133_d_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["s133_c_hora"]) || $this->s133_c_hora != "")
           $resac = db_query("insert into db_acount values($acount,2521,14332,'".AddSlashes(pg_result($resaco,$conresaco,'s133_c_hora'))."','$this->s133_c_hora',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["s133_c_protocolo"]) || $this->s133_c_protocolo != "")
           $resac = db_query("insert into db_acount values($acount,2521,14333,'".AddSlashes(pg_result($resaco,$conresaco,'s133_c_protocolo'))."','$this->s133_c_protocolo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["s133_c_observacoes"]) || $this->s133_c_observacoes != "")
           $resac = db_query("insert into db_acount values($acount,2521,14334,'".AddSlashes(pg_result($resaco,$conresaco,'s133_c_observacoes'))."','$this->s133_c_observacoes',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Confirmacao da realizacao do exame nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->s133_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Confirmacao da realizacao do exame nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->s133_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->s133_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($s133_i_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($s133_i_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,14328,'$s133_i_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,2521,14328,'','".AddSlashes(pg_result($resaco,$iresaco,'s133_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2521,14329,'','".AddSlashes(pg_result($resaco,$iresaco,'s133_i_agendaexames'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2521,14330,'','".AddSlashes(pg_result($resaco,$iresaco,'s133_i_login'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2521,14331,'','".AddSlashes(pg_result($resaco,$iresaco,'s133_d_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2521,14332,'','".AddSlashes(pg_result($resaco,$iresaco,'s133_c_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2521,14333,'','".AddSlashes(pg_result($resaco,$iresaco,'s133_c_protocolo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2521,14334,'','".AddSlashes(pg_result($resaco,$iresaco,'s133_c_observacoes'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from sau_agendaexameconfirma
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($s133_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " s133_i_codigo = $s133_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Confirmacao da realizacao do exame nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$s133_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Confirmacao da realizacao do exame nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$s133_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$s133_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao do recordset 
   function sql_record($sql) { 
     $result = db_query($sql);
     if($result==false){
       $this->numrows    = 0;
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Erro ao selecionar os registros.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $this->numrows = pg_numrows($result);
      if($this->numrows==0){
        $this->erro_banco = "";
        $this->erro_sql   = "Record Vazio na Tabela:sau_agendaexameconfirma";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $s133_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
     $sql = "select ";
     if($campos != "*" ){
       $campos_sql = split("#",$campos);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }else{
       $sql .= $campos;
     }
     $sql .= " from sau_agendaexameconfirma ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = sau_agendaexameconfirma.s133_i_login";
     $sql .= "      inner join sau_agendaexames  on  sau_agendaexames.s113_i_codigo = sau_agendaexameconfirma.s133_i_agendaexames";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = sau_agendaexames.s113_i_login";
     $sql .= "      inner join sau_prestadorhorarios  on  sau_prestadorhorarios.s112_i_codigo = sau_agendaexames.s113_i_prestadorhorarios";
     $sql .= "      inner join cgs_und  on  cgs_und.z01_i_cgsund = sau_agendaexames.s113_i_numcgs";
     $sql2 = "";
     if($dbwhere==""){
       if($s133_i_codigo!=null ){
         $sql2 .= " where sau_agendaexameconfirma.s133_i_codigo = $s133_i_codigo "; 
       } 
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if($ordem != null ){
       $sql .= " order by ";
       $campos_sql = split("#",$ordem);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }
     return $sql;
  }
   // funcao do sql 
   function sql_query_file ( $s133_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
     $sql = "select ";
     if($campos != "*" ){
       $campos_sql = split("#",$campos);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }else{
       $sql .= $campos;
     }
     $sql .= " from sau_agendaexameconfirma ";
     $sql2 = "";
     if($dbwhere==""){
       if($s133_i_codigo!=null ){
         $sql2 .= " where sau_agendaexameconfirma.s133_i_codigo = $s133_i_codigo "; 
       } 
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if($ordem != null ){
       $sql .= " order by ";
       $campos_sql = split("#",$ordem);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }
     return $sql;
  }
}
?>