<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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

//MODULO: escola
//CLASSE DA ENTIDADE controleacessoalunoregistro
class cl_controleacessoalunoregistro { 
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
   var $ed101_sequencial = 0; 
   var $ed101_controleacessoaluno = 0; 
   var $ed101_datasistema_dia = null; 
   var $ed101_datasistema_mes = null; 
   var $ed101_datasistema_ano = null; 
   var $ed101_datasistema = null; 
   var $ed101_horasistema = null; 
   var $ed101_dataleitura_dia = null; 
   var $ed101_dataleitura_mes = null; 
   var $ed101_dataleitura_ano = null; 
   var $ed101_dataleitura = null; 
   var $ed101_horaleitura = null; 
   var $ed101_entrada = 'f'; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ed101_sequencial = int8 = Sequencial interno 
                 ed101_controleacessoaluno = int4 = Sequencial do Controle de Acesso 
                 ed101_datasistema = date = Data leitura Sistema 
                 ed101_horasistema = varchar(8) = Hora da Leitura do sistema 
                 ed101_dataleitura = date = Data da Leitura 
                 ed101_horaleitura = varchar(8) = Hora da Leitura 
                 ed101_entrada = bool = Entrada 
                 ";
   //funcao construtor da classe 
   function cl_controleacessoalunoregistro() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("controleacessoalunoregistro"); 
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
       $this->ed101_sequencial = ($this->ed101_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ed101_sequencial"]:$this->ed101_sequencial);
       $this->ed101_controleacessoaluno = ($this->ed101_controleacessoaluno == ""?@$GLOBALS["HTTP_POST_VARS"]["ed101_controleacessoaluno"]:$this->ed101_controleacessoaluno);
       if($this->ed101_datasistema == ""){
         $this->ed101_datasistema_dia = ($this->ed101_datasistema_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["ed101_datasistema_dia"]:$this->ed101_datasistema_dia);
         $this->ed101_datasistema_mes = ($this->ed101_datasistema_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["ed101_datasistema_mes"]:$this->ed101_datasistema_mes);
         $this->ed101_datasistema_ano = ($this->ed101_datasistema_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["ed101_datasistema_ano"]:$this->ed101_datasistema_ano);
         if($this->ed101_datasistema_dia != ""){
            $this->ed101_datasistema = $this->ed101_datasistema_ano."-".$this->ed101_datasistema_mes."-".$this->ed101_datasistema_dia;
         }
       }
       $this->ed101_horasistema = ($this->ed101_horasistema == ""?@$GLOBALS["HTTP_POST_VARS"]["ed101_horasistema"]:$this->ed101_horasistema);
       if($this->ed101_dataleitura == ""){
         $this->ed101_dataleitura_dia = ($this->ed101_dataleitura_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["ed101_dataleitura_dia"]:$this->ed101_dataleitura_dia);
         $this->ed101_dataleitura_mes = ($this->ed101_dataleitura_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["ed101_dataleitura_mes"]:$this->ed101_dataleitura_mes);
         $this->ed101_dataleitura_ano = ($this->ed101_dataleitura_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["ed101_dataleitura_ano"]:$this->ed101_dataleitura_ano);
         if($this->ed101_dataleitura_dia != ""){
            $this->ed101_dataleitura = $this->ed101_dataleitura_ano."-".$this->ed101_dataleitura_mes."-".$this->ed101_dataleitura_dia;
         }
       }
       $this->ed101_horaleitura = ($this->ed101_horaleitura == ""?@$GLOBALS["HTTP_POST_VARS"]["ed101_horaleitura"]:$this->ed101_horaleitura);
       $this->ed101_entrada = ($this->ed101_entrada == "f"?@$GLOBALS["HTTP_POST_VARS"]["ed101_entrada"]:$this->ed101_entrada);
     }else{
       $this->ed101_sequencial = ($this->ed101_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ed101_sequencial"]:$this->ed101_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($ed101_sequencial){ 
      $this->atualizacampos();
     if($this->ed101_controleacessoaluno == null ){ 
       $this->erro_sql = " Campo Sequencial do Controle de Acesso nao Informado.";
       $this->erro_campo = "ed101_controleacessoaluno";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed101_datasistema == null ){ 
       $this->erro_sql = " Campo Data leitura Sistema nao Informado.";
       $this->erro_campo = "ed101_datasistema_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed101_horasistema == null ){ 
       $this->erro_sql = " Campo Hora da Leitura do sistema nao Informado.";
       $this->erro_campo = "ed101_horasistema";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed101_dataleitura == null ){ 
       $this->erro_sql = " Campo Data da Leitura nao Informado.";
       $this->erro_campo = "ed101_dataleitura_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed101_horaleitura == null ){ 
       $this->erro_sql = " Campo Hora da Leitura nao Informado.";
       $this->erro_campo = "ed101_horaleitura";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed101_entrada == null ){ 
       $this->erro_sql = " Campo Entrada nao Informado.";
       $this->erro_campo = "ed101_entrada";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ed101_sequencial == "" || $ed101_sequencial == null ){
       $result = db_query("select nextval('controleacessoalunoregistro_ed101_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: controleacessoalunoregistro_ed101_sequencial_seq do campo: ed101_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ed101_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from controleacessoalunoregistro_ed101_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $ed101_sequencial)){
         $this->erro_sql = " Campo ed101_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ed101_sequencial = $ed101_sequencial; 
       }
     }
     if(($this->ed101_sequencial == null) || ($this->ed101_sequencial == "") ){ 
       $this->erro_sql = " Campo ed101_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into controleacessoalunoregistro(
                                       ed101_sequencial 
                                      ,ed101_controleacessoaluno 
                                      ,ed101_datasistema 
                                      ,ed101_horasistema 
                                      ,ed101_dataleitura 
                                      ,ed101_horaleitura 
                                      ,ed101_entrada 
                       )
                values (
                                $this->ed101_sequencial 
                               ,$this->ed101_controleacessoaluno 
                               ,".($this->ed101_datasistema == "null" || $this->ed101_datasistema == ""?"null":"'".$this->ed101_datasistema."'")." 
                               ,'$this->ed101_horasistema' 
                               ,".($this->ed101_dataleitura == "null" || $this->ed101_dataleitura == ""?"null":"'".$this->ed101_dataleitura."'")." 
                               ,'$this->ed101_horaleitura' 
                               ,'$this->ed101_entrada' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Regisotros do controle de acesso de alunos ($this->ed101_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Regisotros do controle de acesso de alunos já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Regisotros do controle de acesso de alunos ($this->ed101_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed101_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->ed101_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,18748,'$this->ed101_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,3323,18748,'','".AddSlashes(pg_result($resaco,0,'ed101_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3323,18749,'','".AddSlashes(pg_result($resaco,0,'ed101_controleacessoaluno'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3323,18753,'','".AddSlashes(pg_result($resaco,0,'ed101_datasistema'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3323,18754,'','".AddSlashes(pg_result($resaco,0,'ed101_horasistema'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3323,18751,'','".AddSlashes(pg_result($resaco,0,'ed101_dataleitura'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3323,18752,'','".AddSlashes(pg_result($resaco,0,'ed101_horaleitura'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3323,18755,'','".AddSlashes(pg_result($resaco,0,'ed101_entrada'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($ed101_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update controleacessoalunoregistro set ";
     $virgula = "";
     if(trim($this->ed101_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed101_sequencial"])){ 
       $sql  .= $virgula." ed101_sequencial = $this->ed101_sequencial ";
       $virgula = ",";
       if(trim($this->ed101_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial interno nao Informado.";
         $this->erro_campo = "ed101_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed101_controleacessoaluno)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed101_controleacessoaluno"])){ 
       $sql  .= $virgula." ed101_controleacessoaluno = $this->ed101_controleacessoaluno ";
       $virgula = ",";
       if(trim($this->ed101_controleacessoaluno) == null ){ 
         $this->erro_sql = " Campo Sequencial do Controle de Acesso nao Informado.";
         $this->erro_campo = "ed101_controleacessoaluno";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed101_datasistema)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed101_datasistema_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["ed101_datasistema_dia"] !="") ){ 
       $sql  .= $virgula." ed101_datasistema = '$this->ed101_datasistema' ";
       $virgula = ",";
       if(trim($this->ed101_datasistema) == null ){ 
         $this->erro_sql = " Campo Data leitura Sistema nao Informado.";
         $this->erro_campo = "ed101_datasistema_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["ed101_datasistema_dia"])){ 
         $sql  .= $virgula." ed101_datasistema = null ";
         $virgula = ",";
         if(trim($this->ed101_datasistema) == null ){ 
           $this->erro_sql = " Campo Data leitura Sistema nao Informado.";
           $this->erro_campo = "ed101_datasistema_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->ed101_horasistema)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed101_horasistema"])){ 
       $sql  .= $virgula." ed101_horasistema = '$this->ed101_horasistema' ";
       $virgula = ",";
       if(trim($this->ed101_horasistema) == null ){ 
         $this->erro_sql = " Campo Hora da Leitura do sistema nao Informado.";
         $this->erro_campo = "ed101_horasistema";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed101_dataleitura)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed101_dataleitura_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["ed101_dataleitura_dia"] !="") ){ 
       $sql  .= $virgula." ed101_dataleitura = '$this->ed101_dataleitura' ";
       $virgula = ",";
       if(trim($this->ed101_dataleitura) == null ){ 
         $this->erro_sql = " Campo Data da Leitura nao Informado.";
         $this->erro_campo = "ed101_dataleitura_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["ed101_dataleitura_dia"])){ 
         $sql  .= $virgula." ed101_dataleitura = null ";
         $virgula = ",";
         if(trim($this->ed101_dataleitura) == null ){ 
           $this->erro_sql = " Campo Data da Leitura nao Informado.";
           $this->erro_campo = "ed101_dataleitura_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->ed101_horaleitura)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed101_horaleitura"])){ 
       $sql  .= $virgula." ed101_horaleitura = '$this->ed101_horaleitura' ";
       $virgula = ",";
       if(trim($this->ed101_horaleitura) == null ){ 
         $this->erro_sql = " Campo Hora da Leitura nao Informado.";
         $this->erro_campo = "ed101_horaleitura";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed101_entrada)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed101_entrada"])){ 
       $sql  .= $virgula." ed101_entrada = '$this->ed101_entrada' ";
       $virgula = ",";
       if(trim($this->ed101_entrada) == null ){ 
         $this->erro_sql = " Campo Entrada nao Informado.";
         $this->erro_campo = "ed101_entrada";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($ed101_sequencial!=null){
       $sql .= " ed101_sequencial = $this->ed101_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->ed101_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,18748,'$this->ed101_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed101_sequencial"]) || $this->ed101_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,3323,18748,'".AddSlashes(pg_result($resaco,$conresaco,'ed101_sequencial'))."','$this->ed101_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed101_controleacessoaluno"]) || $this->ed101_controleacessoaluno != "")
           $resac = db_query("insert into db_acount values($acount,3323,18749,'".AddSlashes(pg_result($resaco,$conresaco,'ed101_controleacessoaluno'))."','$this->ed101_controleacessoaluno',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed101_datasistema"]) || $this->ed101_datasistema != "")
           $resac = db_query("insert into db_acount values($acount,3323,18753,'".AddSlashes(pg_result($resaco,$conresaco,'ed101_datasistema'))."','$this->ed101_datasistema',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed101_horasistema"]) || $this->ed101_horasistema != "")
           $resac = db_query("insert into db_acount values($acount,3323,18754,'".AddSlashes(pg_result($resaco,$conresaco,'ed101_horasistema'))."','$this->ed101_horasistema',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed101_dataleitura"]) || $this->ed101_dataleitura != "")
           $resac = db_query("insert into db_acount values($acount,3323,18751,'".AddSlashes(pg_result($resaco,$conresaco,'ed101_dataleitura'))."','$this->ed101_dataleitura',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed101_horaleitura"]) || $this->ed101_horaleitura != "")
           $resac = db_query("insert into db_acount values($acount,3323,18752,'".AddSlashes(pg_result($resaco,$conresaco,'ed101_horaleitura'))."','$this->ed101_horaleitura',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed101_entrada"]) || $this->ed101_entrada != "")
           $resac = db_query("insert into db_acount values($acount,3323,18755,'".AddSlashes(pg_result($resaco,$conresaco,'ed101_entrada'))."','$this->ed101_entrada',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Regisotros do controle de acesso de alunos nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed101_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Regisotros do controle de acesso de alunos nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed101_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed101_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($ed101_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($ed101_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,18748,'$ed101_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,3323,18748,'','".AddSlashes(pg_result($resaco,$iresaco,'ed101_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3323,18749,'','".AddSlashes(pg_result($resaco,$iresaco,'ed101_controleacessoaluno'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3323,18753,'','".AddSlashes(pg_result($resaco,$iresaco,'ed101_datasistema'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3323,18754,'','".AddSlashes(pg_result($resaco,$iresaco,'ed101_horasistema'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3323,18751,'','".AddSlashes(pg_result($resaco,$iresaco,'ed101_dataleitura'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3323,18752,'','".AddSlashes(pg_result($resaco,$iresaco,'ed101_horaleitura'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3323,18755,'','".AddSlashes(pg_result($resaco,$iresaco,'ed101_entrada'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from controleacessoalunoregistro
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ed101_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ed101_sequencial = $ed101_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Regisotros do controle de acesso de alunos nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ed101_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Regisotros do controle de acesso de alunos nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ed101_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ed101_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:controleacessoalunoregistro";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $ed101_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from controleacessoalunoregistro ";
     $sql .= "      inner join controleacessoaluno  on  controleacessoaluno.ed100_sequencial = controleacessoalunoregistro.ed101_controleacessoaluno";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = controleacessoaluno.ed100_id_usuario";
     $sql2 = "";
     if($dbwhere==""){
       if($ed101_sequencial!=null ){
         $sql2 .= " where controleacessoalunoregistro.ed101_sequencial = $ed101_sequencial "; 
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
   function sql_query_file ( $ed101_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from controleacessoalunoregistro ";
     $sql2 = "";
     if($dbwhere==""){
       if($ed101_sequencial!=null ){
         $sql2 .= " where controleacessoalunoregistro.ed101_sequencial = $ed101_sequencial "; 
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
  
  function sql_query_acesso_aluno ( $ed101_sequencial=null,$campos="*",$ordem=null,$dbwhere="") {
     
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
     $sql .= " from controleacessoalunoregistro ";
     $sql .= "      inner join controleacessoaluno                on  ed100_sequencial = ed101_controleacessoaluno";
     $sql .= "      inner join controleacessoalunoregistrovalido  on  ed101_sequencial = ed303_controleacessoalunoregistro";
     $sql .= "      inner join aluno  on  aluno.ed47_i_codigo = controleacessoalunoregistrovalido.ed303_aluno";
     $sql2 = "";
     if($dbwhere==""){
       if($ed101_sequencial!=null ){
         $sql2 .= " where controleacessoalunoregistro.ed101_sequencial = $ed101_sequencial "; 
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