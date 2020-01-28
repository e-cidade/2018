<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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

//MODULO: orcamento
//CLASSE DA ENTIDADE ppaversao
class cl_ppaversao { 
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
   var $o119_sequencial = 0; 
   var $o119_ppalei = 0; 
   var $o119_idusuario = 0; 
   var $o119_versao = 0; 
   var $o119_finalizada = 'f'; 
   var $o119_datainicio_dia = null; 
   var $o119_datainicio_mes = null; 
   var $o119_datainicio_ano = null; 
   var $o119_datainicio = null; 
   var $o119_datatermino_dia = null; 
   var $o119_datatermino_mes = null; 
   var $o119_datatermino_ano = null; 
   var $o119_datatermino = null; 
   var $o119_versaofinal = 'f'; 
   var $o119_ativo = 'f'; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 o119_sequencial = int4 = Código Sequencial 
                 o119_ppalei = int4 = Lei do PPA 
                 o119_idusuario = int4 = Código do Usuário 
                 o119_versao = int4 = Versão 
                 o119_finalizada = bool = Finalizada 
                 o119_datainicio = date = Data de Inicio 
                 o119_datatermino = date = Data de Término 
                 o119_versaofinal = bool = Versão Final 
                 o119_ativo = bool = Ativo 
                 ";
   //funcao construtor da classe 
   function cl_ppaversao() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("ppaversao"); 
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
       $this->o119_sequencial = ($this->o119_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["o119_sequencial"]:$this->o119_sequencial);
       $this->o119_ppalei = ($this->o119_ppalei == ""?@$GLOBALS["HTTP_POST_VARS"]["o119_ppalei"]:$this->o119_ppalei);
       $this->o119_idusuario = ($this->o119_idusuario == ""?@$GLOBALS["HTTP_POST_VARS"]["o119_idusuario"]:$this->o119_idusuario);
       $this->o119_versao = ($this->o119_versao == ""?@$GLOBALS["HTTP_POST_VARS"]["o119_versao"]:$this->o119_versao);
       $this->o119_finalizada = ($this->o119_finalizada == "f"?@$GLOBALS["HTTP_POST_VARS"]["o119_finalizada"]:$this->o119_finalizada);
       if($this->o119_datainicio == ""){
         $this->o119_datainicio_dia = ($this->o119_datainicio_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["o119_datainicio_dia"]:$this->o119_datainicio_dia);
         $this->o119_datainicio_mes = ($this->o119_datainicio_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["o119_datainicio_mes"]:$this->o119_datainicio_mes);
         $this->o119_datainicio_ano = ($this->o119_datainicio_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["o119_datainicio_ano"]:$this->o119_datainicio_ano);
         if($this->o119_datainicio_dia != ""){
            $this->o119_datainicio = $this->o119_datainicio_ano."-".$this->o119_datainicio_mes."-".$this->o119_datainicio_dia;
         }
       }
       if($this->o119_datatermino == ""){
         $this->o119_datatermino_dia = ($this->o119_datatermino_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["o119_datatermino_dia"]:$this->o119_datatermino_dia);
         $this->o119_datatermino_mes = ($this->o119_datatermino_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["o119_datatermino_mes"]:$this->o119_datatermino_mes);
         $this->o119_datatermino_ano = ($this->o119_datatermino_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["o119_datatermino_ano"]:$this->o119_datatermino_ano);
         if($this->o119_datatermino_dia != ""){
            $this->o119_datatermino = $this->o119_datatermino_ano."-".$this->o119_datatermino_mes."-".$this->o119_datatermino_dia;
         }
       }
       $this->o119_versaofinal = ($this->o119_versaofinal == "f"?@$GLOBALS["HTTP_POST_VARS"]["o119_versaofinal"]:$this->o119_versaofinal);
       $this->o119_ativo = ($this->o119_ativo == "f"?@$GLOBALS["HTTP_POST_VARS"]["o119_ativo"]:$this->o119_ativo);
     }else{
       $this->o119_sequencial = ($this->o119_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["o119_sequencial"]:$this->o119_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($o119_sequencial){ 
      $this->atualizacampos();
     if($this->o119_ppalei == null ){ 
       $this->erro_sql = " Campo Lei do PPA nao Informado.";
       $this->erro_campo = "o119_ppalei";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o119_idusuario == null ){ 
       $this->erro_sql = " Campo Código do Usuário nao Informado.";
       $this->erro_campo = "o119_idusuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o119_versao == null ){ 
       $this->erro_sql = " Campo Versão nao Informado.";
       $this->erro_campo = "o119_versao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o119_finalizada == null ){ 
       $this->erro_sql = " Campo Finalizada nao Informado.";
       $this->erro_campo = "o119_finalizada";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o119_datainicio == null ){ 
       $this->erro_sql = " Campo Data de Inicio nao Informado.";
       $this->erro_campo = "o119_datainicio_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o119_datatermino == null ){ 
       $this->o119_datatermino = "null";
     }
     if($this->o119_versaofinal == null ){ 
       $this->erro_sql = " Campo Versão Final nao Informado.";
       $this->erro_campo = "o119_versaofinal";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o119_ativo == null ){ 
       $this->erro_sql = " Campo Ativo nao Informado.";
       $this->erro_campo = "o119_ativo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($o119_sequencial == "" || $o119_sequencial == null ){
       $result = db_query("select nextval('ppaversao_o119_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: ppaversao_o119_sequencial_seq do campo: o119_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->o119_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from ppaversao_o119_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $o119_sequencial)){
         $this->erro_sql = " Campo o119_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->o119_sequencial = $o119_sequencial; 
       }
     }
     if(($this->o119_sequencial == null) || ($this->o119_sequencial == "") ){ 
       $this->erro_sql = " Campo o119_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into ppaversao(
                                       o119_sequencial 
                                      ,o119_ppalei 
                                      ,o119_idusuario 
                                      ,o119_versao 
                                      ,o119_finalizada 
                                      ,o119_datainicio 
                                      ,o119_datatermino 
                                      ,o119_versaofinal 
                                      ,o119_ativo 
                       )
                values (
                                $this->o119_sequencial 
                               ,$this->o119_ppalei 
                               ,$this->o119_idusuario 
                               ,$this->o119_versao 
                               ,'$this->o119_finalizada' 
                               ,".($this->o119_datainicio == "null" || $this->o119_datainicio == ""?"null":"'".$this->o119_datainicio."'")." 
                               ,".($this->o119_datatermino == "null" || $this->o119_datatermino == ""?"null":"'".$this->o119_datatermino."'")." 
                               ,'$this->o119_versaofinal' 
                               ,'$this->o119_ativo' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "versoes do ppa ($this->o119_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "versoes do ppa já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "versoes do ppa ($this->o119_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->o119_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (isset($lSessaoDesativarAccount) && $lSessaoDesativarAccount === false) {

       $resaco = $this->sql_record($this->sql_query_file($this->o119_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,14200,'$this->o119_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,2498,14200,'','".AddSlashes(pg_result($resaco,0,'o119_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2498,14205,'','".AddSlashes(pg_result($resaco,0,'o119_ppalei'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2498,14203,'','".AddSlashes(pg_result($resaco,0,'o119_idusuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2498,14206,'','".AddSlashes(pg_result($resaco,0,'o119_versao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2498,14204,'','".AddSlashes(pg_result($resaco,0,'o119_finalizada'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2498,14201,'','".AddSlashes(pg_result($resaco,0,'o119_datainicio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2498,14202,'','".AddSlashes(pg_result($resaco,0,'o119_datatermino'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2498,14207,'','".AddSlashes(pg_result($resaco,0,'o119_versaofinal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2498,19866,'','".AddSlashes(pg_result($resaco,0,'o119_ativo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($o119_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update ppaversao set ";
     $virgula = "";
     if(trim($this->o119_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o119_sequencial"])){ 
       $sql  .= $virgula." o119_sequencial = $this->o119_sequencial ";
       $virgula = ",";
       if(trim($this->o119_sequencial) == null ){ 
         $this->erro_sql = " Campo Código Sequencial nao Informado.";
         $this->erro_campo = "o119_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o119_ppalei)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o119_ppalei"])){ 
       $sql  .= $virgula." o119_ppalei = $this->o119_ppalei ";
       $virgula = ",";
       if(trim($this->o119_ppalei) == null ){ 
         $this->erro_sql = " Campo Lei do PPA nao Informado.";
         $this->erro_campo = "o119_ppalei";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o119_idusuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o119_idusuario"])){ 
       $sql  .= $virgula." o119_idusuario = $this->o119_idusuario ";
       $virgula = ",";
       if(trim($this->o119_idusuario) == null ){ 
         $this->erro_sql = " Campo Código do Usuário nao Informado.";
         $this->erro_campo = "o119_idusuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o119_versao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o119_versao"])){ 
       $sql  .= $virgula." o119_versao = $this->o119_versao ";
       $virgula = ",";
       if(trim($this->o119_versao) == null ){ 
         $this->erro_sql = " Campo Versão nao Informado.";
         $this->erro_campo = "o119_versao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o119_finalizada)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o119_finalizada"])){ 
       $sql  .= $virgula." o119_finalizada = '$this->o119_finalizada' ";
       $virgula = ",";
       if(trim($this->o119_finalizada) == null ){ 
         $this->erro_sql = " Campo Finalizada nao Informado.";
         $this->erro_campo = "o119_finalizada";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o119_datainicio)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o119_datainicio_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["o119_datainicio_dia"] !="") ){ 
       $sql  .= $virgula." o119_datainicio = '$this->o119_datainicio' ";
       $virgula = ",";
       if(trim($this->o119_datainicio) == null ){ 
         $this->erro_sql = " Campo Data de Inicio nao Informado.";
         $this->erro_campo = "o119_datainicio_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["o119_datainicio_dia"])){ 
         $sql  .= $virgula." o119_datainicio = null ";
         $virgula = ",";
         if(trim($this->o119_datainicio) == null ){ 
           $this->erro_sql = " Campo Data de Inicio nao Informado.";
           $this->erro_campo = "o119_datainicio_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->o119_datatermino)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o119_datatermino_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["o119_datatermino_dia"] !="") ){ 
       $sql  .= $virgula." o119_datatermino = '$this->o119_datatermino' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["o119_datatermino_dia"])){ 
         $sql  .= $virgula." o119_datatermino = null ";
         $virgula = ",";
       }
     }
     if(trim($this->o119_versaofinal)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o119_versaofinal"])){ 
       $sql  .= $virgula." o119_versaofinal = '$this->o119_versaofinal' ";
       $virgula = ",";
       if(trim($this->o119_versaofinal) == null ){ 
         $this->erro_sql = " Campo Versão Final nao Informado.";
         $this->erro_campo = "o119_versaofinal";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o119_ativo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o119_ativo"])){ 
       $sql  .= $virgula." o119_ativo = '$this->o119_ativo' ";
       $virgula = ",";
       if(trim($this->o119_ativo) == null ){ 
         $this->erro_sql = " Campo Ativo nao Informado.";
         $this->erro_campo = "o119_ativo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($o119_sequencial!=null){
       $sql .= " o119_sequencial = $this->o119_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (isset($lSessaoDesativarAccount) && $lSessaoDesativarAccount === false) {

       $resaco = $this->sql_record($this->sql_query_file($this->o119_sequencial));
       if($this->numrows>0){

         for($conresaco=0;$conresaco<$this->numrows;$conresaco++){

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,14200,'$this->o119_sequencial','A')");
           if(isset($GLOBALS["HTTP_POST_VARS"]["o119_sequencial"]) || $this->o119_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,2498,14200,'".AddSlashes(pg_result($resaco,$conresaco,'o119_sequencial'))."','$this->o119_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["o119_ppalei"]) || $this->o119_ppalei != "")
             $resac = db_query("insert into db_acount values($acount,2498,14205,'".AddSlashes(pg_result($resaco,$conresaco,'o119_ppalei'))."','$this->o119_ppalei',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["o119_idusuario"]) || $this->o119_idusuario != "")
             $resac = db_query("insert into db_acount values($acount,2498,14203,'".AddSlashes(pg_result($resaco,$conresaco,'o119_idusuario'))."','$this->o119_idusuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["o119_versao"]) || $this->o119_versao != "")
             $resac = db_query("insert into db_acount values($acount,2498,14206,'".AddSlashes(pg_result($resaco,$conresaco,'o119_versao'))."','$this->o119_versao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["o119_finalizada"]) || $this->o119_finalizada != "")
             $resac = db_query("insert into db_acount values($acount,2498,14204,'".AddSlashes(pg_result($resaco,$conresaco,'o119_finalizada'))."','$this->o119_finalizada',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["o119_datainicio"]) || $this->o119_datainicio != "")
             $resac = db_query("insert into db_acount values($acount,2498,14201,'".AddSlashes(pg_result($resaco,$conresaco,'o119_datainicio'))."','$this->o119_datainicio',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["o119_datatermino"]) || $this->o119_datatermino != "")
             $resac = db_query("insert into db_acount values($acount,2498,14202,'".AddSlashes(pg_result($resaco,$conresaco,'o119_datatermino'))."','$this->o119_datatermino',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["o119_versaofinal"]) || $this->o119_versaofinal != "")
             $resac = db_query("insert into db_acount values($acount,2498,14207,'".AddSlashes(pg_result($resaco,$conresaco,'o119_versaofinal'))."','$this->o119_versaofinal',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["o119_ativo"]) || $this->o119_ativo != "")
             $resac = db_query("insert into db_acount values($acount,2498,19866,'".AddSlashes(pg_result($resaco,$conresaco,'o119_ativo'))."','$this->o119_ativo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "versoes do ppa nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->o119_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "versoes do ppa nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->o119_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->o119_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($o119_sequencial=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (isset($lSessaoDesativarAccount) && $lSessaoDesativarAccount === false) {

       if ($dbwhere==null || $dbwhere=="") {

         $resaco = $this->sql_record($this->sql_query_file($o119_sequencial));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,14200,'$o119_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,2498,14200,'','".AddSlashes(pg_result($resaco,$iresaco,'o119_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2498,14205,'','".AddSlashes(pg_result($resaco,$iresaco,'o119_ppalei'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2498,14203,'','".AddSlashes(pg_result($resaco,$iresaco,'o119_idusuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2498,14206,'','".AddSlashes(pg_result($resaco,$iresaco,'o119_versao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2498,14204,'','".AddSlashes(pg_result($resaco,$iresaco,'o119_finalizada'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2498,14201,'','".AddSlashes(pg_result($resaco,$iresaco,'o119_datainicio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2498,14202,'','".AddSlashes(pg_result($resaco,$iresaco,'o119_datatermino'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2498,14207,'','".AddSlashes(pg_result($resaco,$iresaco,'o119_versaofinal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2498,19866,'','".AddSlashes(pg_result($resaco,$iresaco,'o119_ativo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from ppaversao
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($o119_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " o119_sequencial = $o119_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "versoes do ppa nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$o119_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "versoes do ppa nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$o119_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$o119_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:ppaversao";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $o119_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from ppaversao ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = ppaversao.o119_idusuario";
     $sql .= "      inner join ppalei  on  ppalei.o01_sequencial = ppaversao.o119_ppalei";
     $sql .= "      inner join db_config  on  db_config.codigo = ppalei.o01_instit";
     $sql2 = "";
     if($dbwhere==""){
       if($o119_sequencial!=null ){
         $sql2 .= " where ppaversao.o119_sequencial = $o119_sequencial "; 
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
   function sql_query_file ( $o119_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from ppaversao ";
     $sql2 = "";
     if($dbwhere==""){
       if($o119_sequencial!=null ){
         $sql2 .= " where ppaversao.o119_sequencial = $o119_sequencial "; 
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
  
function sql_query_integracao ( $o119_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from ppaversao ";
     $sql .= "      inner join db_usuarios   on  db_usuarios.id_usuario = ppaversao.o119_idusuario";
     $sql .= "      inner join ppalei        on  ppalei.o01_sequencial = ppaversao.o119_ppalei";
     $sql .= "      inner join db_config     on  db_config.codigo      = ppalei.o01_instit";
     $sql .= "      left join ppaintegracao  on  o119_sequencial        = o123_ppaversao";
     $sql .= "                              and  o123_instit  = ".db_getsession("DB_instit");
     $sql2 = "";
     if($dbwhere==""){
       if($o119_sequencial!=null ){
         $sql2 .= " where ppaversao.o119_sequencial = $o119_sequencial "; 
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