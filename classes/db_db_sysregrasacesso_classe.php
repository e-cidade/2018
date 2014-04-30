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

//MODULO: configuracoes
//CLASSE DA ENTIDADE db_sysregrasacesso
class cl_db_sysregrasacesso { 
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
   var $db46_idacesso = 0; 
   var $db46_dtinicio_dia = null; 
   var $db46_dtinicio_mes = null; 
   var $db46_dtinicio_ano = null; 
   var $db46_dtinicio = null; 
   var $db46_horaini = null; 
   var $db46_datafinal_dia = null; 
   var $db46_datafinal_mes = null; 
   var $db46_datafinal_ano = null; 
   var $db46_datafinal = null; 
   var $db46_horafinal = null; 
   var $db46_id_usuario = 0; 
   var $db46_datacadastd_dia = null; 
   var $db46_datacadastd_mes = null; 
   var $db46_datacadastd_ano = null; 
   var $db46_datacadastd = null; 
   var $db46_observ = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 db46_idacesso = int4 = Código da Regra 
                 db46_dtinicio = date = Data Início 
                 db46_horaini = varchar(5) = Hora Início 
                 db46_datafinal = date = Data Final 
                 db46_horafinal = varchar(5) = Hora Final 
                 db46_id_usuario = int4 = Cod. Usuário 
                 db46_datacadastd = date = Data Cadastro 
                 db46_observ = text = Observação 
                 ";
   //funcao construtor da classe 
   function cl_db_sysregrasacesso() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("db_sysregrasacesso"); 
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
       $this->db46_idacesso = ($this->db46_idacesso == ""?@$GLOBALS["HTTP_POST_VARS"]["db46_idacesso"]:$this->db46_idacesso);
       if($this->db46_dtinicio == ""){
         $this->db46_dtinicio_dia = ($this->db46_dtinicio_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["db46_dtinicio_dia"]:$this->db46_dtinicio_dia);
         $this->db46_dtinicio_mes = ($this->db46_dtinicio_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["db46_dtinicio_mes"]:$this->db46_dtinicio_mes);
         $this->db46_dtinicio_ano = ($this->db46_dtinicio_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["db46_dtinicio_ano"]:$this->db46_dtinicio_ano);
         if($this->db46_dtinicio_dia != ""){
            $this->db46_dtinicio = $this->db46_dtinicio_ano."-".$this->db46_dtinicio_mes."-".$this->db46_dtinicio_dia;
         }
       }
       $this->db46_horaini = ($this->db46_horaini == ""?@$GLOBALS["HTTP_POST_VARS"]["db46_horaini"]:$this->db46_horaini);
       if($this->db46_datafinal == ""){
         $this->db46_datafinal_dia = ($this->db46_datafinal_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["db46_datafinal_dia"]:$this->db46_datafinal_dia);
         $this->db46_datafinal_mes = ($this->db46_datafinal_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["db46_datafinal_mes"]:$this->db46_datafinal_mes);
         $this->db46_datafinal_ano = ($this->db46_datafinal_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["db46_datafinal_ano"]:$this->db46_datafinal_ano);
         if($this->db46_datafinal_dia != ""){
            $this->db46_datafinal = $this->db46_datafinal_ano."-".$this->db46_datafinal_mes."-".$this->db46_datafinal_dia;
         }
       }
       $this->db46_horafinal = ($this->db46_horafinal == ""?@$GLOBALS["HTTP_POST_VARS"]["db46_horafinal"]:$this->db46_horafinal);
       $this->db46_id_usuario = ($this->db46_id_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["db46_id_usuario"]:$this->db46_id_usuario);
       if($this->db46_datacadastd == ""){
         $this->db46_datacadastd_dia = ($this->db46_datacadastd_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["db46_datacadastd_dia"]:$this->db46_datacadastd_dia);
         $this->db46_datacadastd_mes = ($this->db46_datacadastd_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["db46_datacadastd_mes"]:$this->db46_datacadastd_mes);
         $this->db46_datacadastd_ano = ($this->db46_datacadastd_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["db46_datacadastd_ano"]:$this->db46_datacadastd_ano);
         if($this->db46_datacadastd_dia != ""){
            $this->db46_datacadastd = $this->db46_datacadastd_ano."-".$this->db46_datacadastd_mes."-".$this->db46_datacadastd_dia;
         }
       }
       $this->db46_observ = ($this->db46_observ == ""?@$GLOBALS["HTTP_POST_VARS"]["db46_observ"]:$this->db46_observ);
     }else{
       $this->db46_idacesso = ($this->db46_idacesso == ""?@$GLOBALS["HTTP_POST_VARS"]["db46_idacesso"]:$this->db46_idacesso);
     }
   }
   // funcao para inclusao
   function incluir ($db46_idacesso){ 
      $this->atualizacampos();
     if($this->db46_dtinicio == null ){ 
       $this->erro_sql = " Campo Data Início nao Informado.";
       $this->erro_campo = "db46_dtinicio_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->db46_horaini == null ){ 
       $this->erro_sql = " Campo Hora Início nao Informado.";
       $this->erro_campo = "db46_horaini";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->db46_datafinal == null ){ 
       $this->erro_sql = " Campo Data Final nao Informado.";
       $this->erro_campo = "db46_datafinal_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->db46_horafinal == null ){ 
       $this->erro_sql = " Campo Hora Final nao Informado.";
       $this->erro_campo = "db46_horafinal";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->db46_id_usuario == null ){ 
       $this->erro_sql = " Campo Cod. Usuário nao Informado.";
       $this->erro_campo = "db46_id_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->db46_datacadastd == null ){ 
       $this->erro_sql = " Campo Data Cadastro nao Informado.";
       $this->erro_campo = "db46_datacadastd_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($db46_idacesso == "" || $db46_idacesso == null ){
       $result = db_query("select nextval('db_sysregrasacesso_db46_idacesso_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: db_sysregrasacesso_db46_idacesso_seq do campo: db46_idacesso"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->db46_idacesso = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from db_sysregrasacesso_db46_idacesso_seq");
       if(($result != false) && (pg_result($result,0,0) < $db46_idacesso)){
         $this->erro_sql = " Campo db46_idacesso maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->db46_idacesso = $db46_idacesso; 
       }
     }
     if(($this->db46_idacesso == null) || ($this->db46_idacesso == "") ){ 
       $this->erro_sql = " Campo db46_idacesso nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into db_sysregrasacesso(
                                       db46_idacesso 
                                      ,db46_dtinicio 
                                      ,db46_horaini 
                                      ,db46_datafinal 
                                      ,db46_horafinal 
                                      ,db46_id_usuario 
                                      ,db46_datacadastd 
                                      ,db46_observ 
                       )
                values (
                                $this->db46_idacesso 
                               ,".($this->db46_dtinicio == "null" || $this->db46_dtinicio == ""?"null":"'".$this->db46_dtinicio."'")." 
                               ,'$this->db46_horaini' 
                               ,".($this->db46_datafinal == "null" || $this->db46_datafinal == ""?"null":"'".$this->db46_datafinal."'")." 
                               ,'$this->db46_horafinal' 
                               ,$this->db46_id_usuario 
                               ,".($this->db46_datacadastd == "null" || $this->db46_datacadastd == ""?"null":"'".$this->db46_datacadastd."'")." 
                               ,'$this->db46_observ' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Regras de Acesso so Sistema ($this->db46_idacesso) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Regras de Acesso so Sistema já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Regras de Acesso so Sistema ($this->db46_idacesso) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->db46_idacesso;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->db46_idacesso));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,10260,'$this->db46_idacesso','I')");
       $resac = db_query("insert into db_acount values($acount,1772,10260,'','".AddSlashes(pg_result($resaco,0,'db46_idacesso'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1772,10261,'','".AddSlashes(pg_result($resaco,0,'db46_dtinicio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1772,10262,'','".AddSlashes(pg_result($resaco,0,'db46_horaini'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1772,10263,'','".AddSlashes(pg_result($resaco,0,'db46_datafinal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1772,10264,'','".AddSlashes(pg_result($resaco,0,'db46_horafinal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1772,10265,'','".AddSlashes(pg_result($resaco,0,'db46_id_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1772,10266,'','".AddSlashes(pg_result($resaco,0,'db46_datacadastd'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1772,10267,'','".AddSlashes(pg_result($resaco,0,'db46_observ'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($db46_idacesso=null) { 
      $this->atualizacampos();
     $sql = " update db_sysregrasacesso set ";
     $virgula = "";
     if(trim($this->db46_idacesso)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db46_idacesso"])){ 
       $sql  .= $virgula." db46_idacesso = $this->db46_idacesso ";
       $virgula = ",";
       if(trim($this->db46_idacesso) == null ){ 
         $this->erro_sql = " Campo Código da Regra nao Informado.";
         $this->erro_campo = "db46_idacesso";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db46_dtinicio)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db46_dtinicio_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["db46_dtinicio_dia"] !="") ){ 
       $sql  .= $virgula." db46_dtinicio = '$this->db46_dtinicio' ";
       $virgula = ",";
       if(trim($this->db46_dtinicio) == null ){ 
         $this->erro_sql = " Campo Data Início nao Informado.";
         $this->erro_campo = "db46_dtinicio_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["db46_dtinicio_dia"])){ 
         $sql  .= $virgula." db46_dtinicio = null ";
         $virgula = ",";
         if(trim($this->db46_dtinicio) == null ){ 
           $this->erro_sql = " Campo Data Início nao Informado.";
           $this->erro_campo = "db46_dtinicio_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->db46_horaini)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db46_horaini"])){ 
       $sql  .= $virgula." db46_horaini = '$this->db46_horaini' ";
       $virgula = ",";
       if(trim($this->db46_horaini) == null ){ 
         $this->erro_sql = " Campo Hora Início nao Informado.";
         $this->erro_campo = "db46_horaini";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db46_datafinal)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db46_datafinal_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["db46_datafinal_dia"] !="") ){ 
       $sql  .= $virgula." db46_datafinal = '$this->db46_datafinal' ";
       $virgula = ",";
       if(trim($this->db46_datafinal) == null ){ 
         $this->erro_sql = " Campo Data Final nao Informado.";
         $this->erro_campo = "db46_datafinal_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["db46_datafinal_dia"])){ 
         $sql  .= $virgula." db46_datafinal = null ";
         $virgula = ",";
         if(trim($this->db46_datafinal) == null ){ 
           $this->erro_sql = " Campo Data Final nao Informado.";
           $this->erro_campo = "db46_datafinal_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->db46_horafinal)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db46_horafinal"])){ 
       $sql  .= $virgula." db46_horafinal = '$this->db46_horafinal' ";
       $virgula = ",";
       if(trim($this->db46_horafinal) == null ){ 
         $this->erro_sql = " Campo Hora Final nao Informado.";
         $this->erro_campo = "db46_horafinal";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db46_id_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db46_id_usuario"])){ 
       $sql  .= $virgula." db46_id_usuario = $this->db46_id_usuario ";
       $virgula = ",";
       if(trim($this->db46_id_usuario) == null ){ 
         $this->erro_sql = " Campo Cod. Usuário nao Informado.";
         $this->erro_campo = "db46_id_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db46_datacadastd)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db46_datacadastd_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["db46_datacadastd_dia"] !="") ){ 
       $sql  .= $virgula." db46_datacadastd = '$this->db46_datacadastd' ";
       $virgula = ",";
       if(trim($this->db46_datacadastd) == null ){ 
         $this->erro_sql = " Campo Data Cadastro nao Informado.";
         $this->erro_campo = "db46_datacadastd_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["db46_datacadastd_dia"])){ 
         $sql  .= $virgula." db46_datacadastd = null ";
         $virgula = ",";
         if(trim($this->db46_datacadastd) == null ){ 
           $this->erro_sql = " Campo Data Cadastro nao Informado.";
           $this->erro_campo = "db46_datacadastd_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->db46_observ)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db46_observ"])){ 
       $sql  .= $virgula." db46_observ = '$this->db46_observ' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($db46_idacesso!=null){
       $sql .= " db46_idacesso = $this->db46_idacesso";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->db46_idacesso));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,10260,'$this->db46_idacesso','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db46_idacesso"]))
           $resac = db_query("insert into db_acount values($acount,1772,10260,'".AddSlashes(pg_result($resaco,$conresaco,'db46_idacesso'))."','$this->db46_idacesso',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db46_dtinicio"]))
           $resac = db_query("insert into db_acount values($acount,1772,10261,'".AddSlashes(pg_result($resaco,$conresaco,'db46_dtinicio'))."','$this->db46_dtinicio',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db46_horaini"]))
           $resac = db_query("insert into db_acount values($acount,1772,10262,'".AddSlashes(pg_result($resaco,$conresaco,'db46_horaini'))."','$this->db46_horaini',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db46_datafinal"]))
           $resac = db_query("insert into db_acount values($acount,1772,10263,'".AddSlashes(pg_result($resaco,$conresaco,'db46_datafinal'))."','$this->db46_datafinal',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db46_horafinal"]))
           $resac = db_query("insert into db_acount values($acount,1772,10264,'".AddSlashes(pg_result($resaco,$conresaco,'db46_horafinal'))."','$this->db46_horafinal',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db46_id_usuario"]))
           $resac = db_query("insert into db_acount values($acount,1772,10265,'".AddSlashes(pg_result($resaco,$conresaco,'db46_id_usuario'))."','$this->db46_id_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db46_datacadastd"]))
           $resac = db_query("insert into db_acount values($acount,1772,10266,'".AddSlashes(pg_result($resaco,$conresaco,'db46_datacadastd'))."','$this->db46_datacadastd',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db46_observ"]))
           $resac = db_query("insert into db_acount values($acount,1772,10267,'".AddSlashes(pg_result($resaco,$conresaco,'db46_observ'))."','$this->db46_observ',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Regras de Acesso so Sistema nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->db46_idacesso;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Regras de Acesso so Sistema nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->db46_idacesso;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->db46_idacesso;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($db46_idacesso=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($db46_idacesso));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,10260,'$db46_idacesso','E')");
         $resac = db_query("insert into db_acount values($acount,1772,10260,'','".AddSlashes(pg_result($resaco,$iresaco,'db46_idacesso'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1772,10261,'','".AddSlashes(pg_result($resaco,$iresaco,'db46_dtinicio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1772,10262,'','".AddSlashes(pg_result($resaco,$iresaco,'db46_horaini'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1772,10263,'','".AddSlashes(pg_result($resaco,$iresaco,'db46_datafinal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1772,10264,'','".AddSlashes(pg_result($resaco,$iresaco,'db46_horafinal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1772,10265,'','".AddSlashes(pg_result($resaco,$iresaco,'db46_id_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1772,10266,'','".AddSlashes(pg_result($resaco,$iresaco,'db46_datacadastd'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1772,10267,'','".AddSlashes(pg_result($resaco,$iresaco,'db46_observ'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from db_sysregrasacesso
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($db46_idacesso != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " db46_idacesso = $db46_idacesso ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Regras de Acesso so Sistema nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$db46_idacesso;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Regras de Acesso so Sistema nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$db46_idacesso;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$db46_idacesso;
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
        $this->erro_sql   = "Record Vazio na Tabela:db_sysregrasacesso";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $db46_idacesso=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from db_sysregrasacesso ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = db_sysregrasacesso.db46_id_usuario";
     $sql2 = "";
     if($dbwhere==""){
       if($db46_idacesso!=null ){
         $sql2 .= " where db_sysregrasacesso.db46_idacesso = $db46_idacesso "; 
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
   function sql_query_file ( $db46_idacesso=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from db_sysregrasacesso ";
     $sql2 = "";
     if($dbwhere==""){
       if($db46_idacesso!=null ){
         $sql2 .= " where db_sysregrasacesso.db46_idacesso = $db46_idacesso "; 
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