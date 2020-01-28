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

//MODULO: itbi
//CLASSE DA ENTIDADE itbialt
class cl_itbialt { 
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
   var $it30_sequencial = 0; 
   var $it30_guia = 0; 
   var $it30_usuario = 0; 
   var $it30_dataalt_dia = null; 
   var $it30_dataalt_mes = null; 
   var $it30_dataalt_ano = null; 
   var $it30_dataalt = null; 
   var $it30_hora = null; 
   var $it30_dataliberacao_dia = null; 
   var $it30_dataliberacao_mes = null; 
   var $it30_dataliberacao_ano = null; 
   var $it30_dataliberacao = null; 
   var $it30_datavenc_dia = null; 
   var $it30_datavenc_mes = null; 
   var $it30_datavenc_ano = null; 
   var $it30_datavenc = null; 
   var $it30_dataitbi_dia = null; 
   var $it30_dataitbi_mes = null; 
   var $it30_dataitbi_ano = null; 
   var $it30_dataitbi = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 it30_sequencial = int4 = Codigo sequencial 
                 it30_guia = int8 = Código da ITBI 
                 it30_usuario = int4 = Cod. Usuário 
                 it30_dataalt = date = Data da alteração 
                 it30_hora = char(5) = Hora 
                 it30_dataliberacao = date = Data da liberação 
                 it30_datavenc = date = Data de vencimento 
                 it30_dataitbi = date = Data da itbi 
                 ";
   //funcao construtor da classe 
   function cl_itbialt() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("itbialt"); 
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
       $this->it30_sequencial = ($this->it30_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["it30_sequencial"]:$this->it30_sequencial);
       $this->it30_guia = ($this->it30_guia == ""?@$GLOBALS["HTTP_POST_VARS"]["it30_guia"]:$this->it30_guia);
       $this->it30_usuario = ($this->it30_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["it30_usuario"]:$this->it30_usuario);
       if($this->it30_dataalt == ""){
         $this->it30_dataalt_dia = ($this->it30_dataalt_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["it30_dataalt_dia"]:$this->it30_dataalt_dia);
         $this->it30_dataalt_mes = ($this->it30_dataalt_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["it30_dataalt_mes"]:$this->it30_dataalt_mes);
         $this->it30_dataalt_ano = ($this->it30_dataalt_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["it30_dataalt_ano"]:$this->it30_dataalt_ano);
         if($this->it30_dataalt_dia != ""){
            $this->it30_dataalt = $this->it30_dataalt_ano."-".$this->it30_dataalt_mes."-".$this->it30_dataalt_dia;
         }
       }
       $this->it30_hora = ($this->it30_hora == ""?@$GLOBALS["HTTP_POST_VARS"]["it30_hora"]:$this->it30_hora);
       if($this->it30_dataliberacao == ""){
         $this->it30_dataliberacao_dia = ($this->it30_dataliberacao_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["it30_dataliberacao_dia"]:$this->it30_dataliberacao_dia);
         $this->it30_dataliberacao_mes = ($this->it30_dataliberacao_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["it30_dataliberacao_mes"]:$this->it30_dataliberacao_mes);
         $this->it30_dataliberacao_ano = ($this->it30_dataliberacao_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["it30_dataliberacao_ano"]:$this->it30_dataliberacao_ano);
         if($this->it30_dataliberacao_dia != ""){
            $this->it30_dataliberacao = $this->it30_dataliberacao_ano."-".$this->it30_dataliberacao_mes."-".$this->it30_dataliberacao_dia;
         }
       }
       if($this->it30_datavenc == ""){
         $this->it30_datavenc_dia = ($this->it30_datavenc_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["it30_datavenc_dia"]:$this->it30_datavenc_dia);
         $this->it30_datavenc_mes = ($this->it30_datavenc_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["it30_datavenc_mes"]:$this->it30_datavenc_mes);
         $this->it30_datavenc_ano = ($this->it30_datavenc_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["it30_datavenc_ano"]:$this->it30_datavenc_ano);
         if($this->it30_datavenc_dia != ""){
            $this->it30_datavenc = $this->it30_datavenc_ano."-".$this->it30_datavenc_mes."-".$this->it30_datavenc_dia;
         }
       }
       if($this->it30_dataitbi == ""){
         $this->it30_dataitbi_dia = ($this->it30_dataitbi_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["it30_dataitbi_dia"]:$this->it30_dataitbi_dia);
         $this->it30_dataitbi_mes = ($this->it30_dataitbi_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["it30_dataitbi_mes"]:$this->it30_dataitbi_mes);
         $this->it30_dataitbi_ano = ($this->it30_dataitbi_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["it30_dataitbi_ano"]:$this->it30_dataitbi_ano);
         if($this->it30_dataitbi_dia != ""){
            $this->it30_dataitbi = $this->it30_dataitbi_ano."-".$this->it30_dataitbi_mes."-".$this->it30_dataitbi_dia;
         }
       }
     }else{
       $this->it30_sequencial = ($this->it30_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["it30_sequencial"]:$this->it30_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($it30_sequencial){ 
      $this->atualizacampos();
     if($this->it30_guia == null ){ 
       $this->erro_sql = " Campo Código da ITBI nao Informado.";
       $this->erro_campo = "it30_guia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->it30_usuario == null ){ 
       $this->erro_sql = " Campo Cod. Usuário nao Informado.";
       $this->erro_campo = "it30_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->it30_dataalt == null ){ 
       $this->erro_sql = " Campo Data da alteração nao Informado.";
       $this->erro_campo = "it30_dataalt_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->it30_hora == null ){ 
       $this->erro_sql = " Campo Hora nao Informado.";
       $this->erro_campo = "it30_hora";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->it30_dataliberacao == null ){ 
       $this->erro_sql = " Campo Data da liberação nao Informado.";
       $this->erro_campo = "it30_dataliberacao_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->it30_datavenc == null ){ 
       $this->erro_sql = " Campo Data de vencimento nao Informado.";
       $this->erro_campo = "it30_datavenc_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->it30_dataitbi == null ){ 
       $this->erro_sql = " Campo Data da itbi nao Informado.";
       $this->erro_campo = "it30_dataitbi_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($it30_sequencial == "" || $it30_sequencial == null ){
       $result = db_query("select nextval('itbialt_it30_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: itbialt_it30_sequencial_seq do campo: it30_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->it30_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from itbialt_it30_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $it30_sequencial)){
         $this->erro_sql = " Campo it30_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->it30_sequencial = $it30_sequencial; 
       }
     }
     if(($this->it30_sequencial == null) || ($this->it30_sequencial == "") ){ 
       $this->erro_sql = " Campo it30_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into itbialt(
                                       it30_sequencial 
                                      ,it30_guia 
                                      ,it30_usuario 
                                      ,it30_dataalt 
                                      ,it30_hora 
                                      ,it30_dataliberacao 
                                      ,it30_datavenc 
                                      ,it30_dataitbi 
                       )
                values (
                                $this->it30_sequencial 
                               ,$this->it30_guia 
                               ,$this->it30_usuario 
                               ,".($this->it30_dataalt == "null" || $this->it30_dataalt == ""?"null":"'".$this->it30_dataalt."'")." 
                               ,'$this->it30_hora' 
                               ,".($this->it30_dataliberacao == "null" || $this->it30_dataliberacao == ""?"null":"'".$this->it30_dataliberacao."'")." 
                               ,".($this->it30_datavenc == "null" || $this->it30_datavenc == ""?"null":"'".$this->it30_datavenc."'")." 
                               ,".($this->it30_dataitbi == "null" || $this->it30_dataitbi == ""?"null":"'".$this->it30_dataitbi."'")." 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Alterações da itbi ($this->it30_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Alterações da itbi já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Alterações da itbi ($this->it30_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->it30_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->it30_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,13621,'$this->it30_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,2386,13621,'','".AddSlashes(pg_result($resaco,0,'it30_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2386,13622,'','".AddSlashes(pg_result($resaco,0,'it30_guia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2386,13623,'','".AddSlashes(pg_result($resaco,0,'it30_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2386,13624,'','".AddSlashes(pg_result($resaco,0,'it30_dataalt'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2386,13625,'','".AddSlashes(pg_result($resaco,0,'it30_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2386,13628,'','".AddSlashes(pg_result($resaco,0,'it30_dataliberacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2386,13627,'','".AddSlashes(pg_result($resaco,0,'it30_datavenc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2386,13626,'','".AddSlashes(pg_result($resaco,0,'it30_dataitbi'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($it30_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update itbialt set ";
     $virgula = "";
     if(trim($this->it30_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["it30_sequencial"])){ 
       $sql  .= $virgula." it30_sequencial = $this->it30_sequencial ";
       $virgula = ",";
       if(trim($this->it30_sequencial) == null ){ 
         $this->erro_sql = " Campo Codigo sequencial nao Informado.";
         $this->erro_campo = "it30_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->it30_guia)!="" || isset($GLOBALS["HTTP_POST_VARS"]["it30_guia"])){ 
       $sql  .= $virgula." it30_guia = $this->it30_guia ";
       $virgula = ",";
       if(trim($this->it30_guia) == null ){ 
         $this->erro_sql = " Campo Código da ITBI nao Informado.";
         $this->erro_campo = "it30_guia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->it30_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["it30_usuario"])){ 
       $sql  .= $virgula." it30_usuario = $this->it30_usuario ";
       $virgula = ",";
       if(trim($this->it30_usuario) == null ){ 
         $this->erro_sql = " Campo Cod. Usuário nao Informado.";
         $this->erro_campo = "it30_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->it30_dataalt)!="" || isset($GLOBALS["HTTP_POST_VARS"]["it30_dataalt_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["it30_dataalt_dia"] !="") ){ 
       $sql  .= $virgula." it30_dataalt = '$this->it30_dataalt' ";
       $virgula = ",";
       if(trim($this->it30_dataalt) == null ){ 
         $this->erro_sql = " Campo Data da alteração nao Informado.";
         $this->erro_campo = "it30_dataalt_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["it30_dataalt_dia"])){ 
         $sql  .= $virgula." it30_dataalt = null ";
         $virgula = ",";
         if(trim($this->it30_dataalt) == null ){ 
           $this->erro_sql = " Campo Data da alteração nao Informado.";
           $this->erro_campo = "it30_dataalt_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->it30_hora)!="" || isset($GLOBALS["HTTP_POST_VARS"]["it30_hora"])){ 
       $sql  .= $virgula." it30_hora = '$this->it30_hora' ";
       $virgula = ",";
       if(trim($this->it30_hora) == null ){ 
         $this->erro_sql = " Campo Hora nao Informado.";
         $this->erro_campo = "it30_hora";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->it30_dataliberacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["it30_dataliberacao_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["it30_dataliberacao_dia"] !="") ){ 
       $sql  .= $virgula." it30_dataliberacao = '$this->it30_dataliberacao' ";
       $virgula = ",";
       if(trim($this->it30_dataliberacao) == null ){ 
         $this->erro_sql = " Campo Data da liberação nao Informado.";
         $this->erro_campo = "it30_dataliberacao_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["it30_dataliberacao_dia"])){ 
         $sql  .= $virgula." it30_dataliberacao = null ";
         $virgula = ",";
         if(trim($this->it30_dataliberacao) == null ){ 
           $this->erro_sql = " Campo Data da liberação nao Informado.";
           $this->erro_campo = "it30_dataliberacao_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->it30_datavenc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["it30_datavenc_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["it30_datavenc_dia"] !="") ){ 
       $sql  .= $virgula." it30_datavenc = '$this->it30_datavenc' ";
       $virgula = ",";
       if(trim($this->it30_datavenc) == null ){ 
         $this->erro_sql = " Campo Data de vencimento nao Informado.";
         $this->erro_campo = "it30_datavenc_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["it30_datavenc_dia"])){ 
         $sql  .= $virgula." it30_datavenc = null ";
         $virgula = ",";
         if(trim($this->it30_datavenc) == null ){ 
           $this->erro_sql = " Campo Data de vencimento nao Informado.";
           $this->erro_campo = "it30_datavenc_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->it30_dataitbi)!="" || isset($GLOBALS["HTTP_POST_VARS"]["it30_dataitbi_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["it30_dataitbi_dia"] !="") ){ 
       $sql  .= $virgula." it30_dataitbi = '$this->it30_dataitbi' ";
       $virgula = ",";
       if(trim($this->it30_dataitbi) == null ){ 
         $this->erro_sql = " Campo Data da itbi nao Informado.";
         $this->erro_campo = "it30_dataitbi_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["it30_dataitbi_dia"])){ 
         $sql  .= $virgula." it30_dataitbi = null ";
         $virgula = ",";
         if(trim($this->it30_dataitbi) == null ){ 
           $this->erro_sql = " Campo Data da itbi nao Informado.";
           $this->erro_campo = "it30_dataitbi_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     $sql .= " where ";
     if($it30_sequencial!=null){
       $sql .= " it30_sequencial = $this->it30_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->it30_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,13621,'$this->it30_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["it30_sequencial"]))
           $resac = db_query("insert into db_acount values($acount,2386,13621,'".AddSlashes(pg_result($resaco,$conresaco,'it30_sequencial'))."','$this->it30_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["it30_guia"]))
           $resac = db_query("insert into db_acount values($acount,2386,13622,'".AddSlashes(pg_result($resaco,$conresaco,'it30_guia'))."','$this->it30_guia',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["it30_usuario"]))
           $resac = db_query("insert into db_acount values($acount,2386,13623,'".AddSlashes(pg_result($resaco,$conresaco,'it30_usuario'))."','$this->it30_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["it30_dataalt"]))
           $resac = db_query("insert into db_acount values($acount,2386,13624,'".AddSlashes(pg_result($resaco,$conresaco,'it30_dataalt'))."','$this->it30_dataalt',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["it30_hora"]))
           $resac = db_query("insert into db_acount values($acount,2386,13625,'".AddSlashes(pg_result($resaco,$conresaco,'it30_hora'))."','$this->it30_hora',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["it30_dataliberacao"]))
           $resac = db_query("insert into db_acount values($acount,2386,13628,'".AddSlashes(pg_result($resaco,$conresaco,'it30_dataliberacao'))."','$this->it30_dataliberacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["it30_datavenc"]))
           $resac = db_query("insert into db_acount values($acount,2386,13627,'".AddSlashes(pg_result($resaco,$conresaco,'it30_datavenc'))."','$this->it30_datavenc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["it30_dataitbi"]))
           $resac = db_query("insert into db_acount values($acount,2386,13626,'".AddSlashes(pg_result($resaco,$conresaco,'it30_dataitbi'))."','$this->it30_dataitbi',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Alterações da itbi nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->it30_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Alterações da itbi nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->it30_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->it30_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($it30_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($it30_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,13621,'$it30_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,2386,13621,'','".AddSlashes(pg_result($resaco,$iresaco,'it30_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2386,13622,'','".AddSlashes(pg_result($resaco,$iresaco,'it30_guia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2386,13623,'','".AddSlashes(pg_result($resaco,$iresaco,'it30_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2386,13624,'','".AddSlashes(pg_result($resaco,$iresaco,'it30_dataalt'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2386,13625,'','".AddSlashes(pg_result($resaco,$iresaco,'it30_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2386,13628,'','".AddSlashes(pg_result($resaco,$iresaco,'it30_dataliberacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2386,13627,'','".AddSlashes(pg_result($resaco,$iresaco,'it30_datavenc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2386,13626,'','".AddSlashes(pg_result($resaco,$iresaco,'it30_dataitbi'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from itbialt
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($it30_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " it30_sequencial = $it30_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Alterações da itbi nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$it30_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Alterações da itbi nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$it30_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$it30_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:itbialt";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $it30_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from itbialt ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = itbialt.it30_usuario";
     $sql .= "      inner join itbiavalia  on  itbiavalia.it14_guia = itbialt.it30_guia";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = itbiavalia.it14_id_usuario";
     $sql .= "      inner join itbi  as a on   a.it01_guia = itbiavalia.it14_guia";
     $sql2 = "";
     if($dbwhere==""){
       if($it30_sequencial!=null ){
         $sql2 .= " where itbialt.it30_sequencial = $it30_sequencial "; 
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
   function sql_query_file ( $it30_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from itbialt ";
     $sql2 = "";
     if($dbwhere==""){
       if($it30_sequencial!=null ){
         $sql2 .= " where itbialt.it30_sequencial = $it30_sequencial "; 
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