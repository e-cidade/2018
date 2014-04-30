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

//MODULO: Acordos
//CLASSE DA ENTIDADE acordomovimentacao
class cl_acordomovimentacao { 
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
   var $ac10_sequencial = 0; 
   var $ac10_acordomovimentacaotipo = 0; 
   var $ac10_acordo = 0; 
   var $ac10_id_usuario = 0; 
   var $ac10_datamovimento_dia = null; 
   var $ac10_datamovimento_mes = null; 
   var $ac10_datamovimento_ano = null; 
   var $ac10_datamovimento = null; 
   var $ac10_hora = null; 
   var $ac10_obs = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ac10_sequencial = int4 = Sequencial 
                 ac10_acordomovimentacaotipo = int4 = Acordo Movimentação Tipo 
                 ac10_acordo = int4 = Acordo 
                 ac10_id_usuario = int4 = Código Usúario 
                 ac10_datamovimento = date = Data Movimentação 
                 ac10_hora = char(5) = Hora 
                 ac10_obs = text = Observação 
                 ";
   //funcao construtor da classe 
   function cl_acordomovimentacao() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("acordomovimentacao"); 
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
       $this->ac10_sequencial = ($this->ac10_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ac10_sequencial"]:$this->ac10_sequencial);
       $this->ac10_acordomovimentacaotipo = ($this->ac10_acordomovimentacaotipo == ""?@$GLOBALS["HTTP_POST_VARS"]["ac10_acordomovimentacaotipo"]:$this->ac10_acordomovimentacaotipo);
       $this->ac10_acordo = ($this->ac10_acordo == ""?@$GLOBALS["HTTP_POST_VARS"]["ac10_acordo"]:$this->ac10_acordo);
       $this->ac10_id_usuario = ($this->ac10_id_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["ac10_id_usuario"]:$this->ac10_id_usuario);
       if($this->ac10_datamovimento == ""){
         $this->ac10_datamovimento_dia = ($this->ac10_datamovimento_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["ac10_datamovimento_dia"]:$this->ac10_datamovimento_dia);
         $this->ac10_datamovimento_mes = ($this->ac10_datamovimento_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["ac10_datamovimento_mes"]:$this->ac10_datamovimento_mes);
         $this->ac10_datamovimento_ano = ($this->ac10_datamovimento_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["ac10_datamovimento_ano"]:$this->ac10_datamovimento_ano);
         if($this->ac10_datamovimento_dia != ""){
            $this->ac10_datamovimento = $this->ac10_datamovimento_ano."-".$this->ac10_datamovimento_mes."-".$this->ac10_datamovimento_dia;
         }
       }
       $this->ac10_hora = ($this->ac10_hora == ""?@$GLOBALS["HTTP_POST_VARS"]["ac10_hora"]:$this->ac10_hora);
       $this->ac10_obs = ($this->ac10_obs == ""?@$GLOBALS["HTTP_POST_VARS"]["ac10_obs"]:$this->ac10_obs);
     }else{
       $this->ac10_sequencial = ($this->ac10_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ac10_sequencial"]:$this->ac10_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($ac10_sequencial){ 
      $this->atualizacampos();
     if($this->ac10_acordomovimentacaotipo == null ){ 
       $this->erro_sql = " Campo Acordo Movimentação Tipo nao Informado.";
       $this->erro_campo = "ac10_acordomovimentacaotipo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ac10_acordo == null ){ 
       $this->erro_sql = " Campo Acordo nao Informado.";
       $this->erro_campo = "ac10_acordo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ac10_id_usuario == null ){ 
       $this->erro_sql = " Campo Código Usúario nao Informado.";
       $this->erro_campo = "ac10_id_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ac10_datamovimento == null ){ 
       $this->erro_sql = " Campo Data Movimentação nao Informado.";
       $this->erro_campo = "ac10_datamovimento_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ac10_hora == null ){ 
       $this->erro_sql = " Campo Hora nao Informado.";
       $this->erro_campo = "ac10_hora";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ac10_obs == null ){ 
       $this->erro_sql = " Campo Observação nao Informado.";
       $this->erro_campo = "ac10_obs";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ac10_sequencial == "" || $ac10_sequencial == null ){
       $result = db_query("select nextval('acordomovimentacao_ac10_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: acordomovimentacao_ac10_sequencial_seq do campo: ac10_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ac10_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from acordomovimentacao_ac10_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $ac10_sequencial)){
         $this->erro_sql = " Campo ac10_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ac10_sequencial = $ac10_sequencial; 
       }
     }
     if(($this->ac10_sequencial == null) || ($this->ac10_sequencial == "") ){ 
       $this->erro_sql = " Campo ac10_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into acordomovimentacao(
                                       ac10_sequencial 
                                      ,ac10_acordomovimentacaotipo 
                                      ,ac10_acordo 
                                      ,ac10_id_usuario 
                                      ,ac10_datamovimento 
                                      ,ac10_hora 
                                      ,ac10_obs 
                       )
                values (
                                $this->ac10_sequencial 
                               ,$this->ac10_acordomovimentacaotipo 
                               ,$this->ac10_acordo 
                               ,$this->ac10_id_usuario 
                               ,".($this->ac10_datamovimento == "null" || $this->ac10_datamovimento == ""?"null":"'".$this->ac10_datamovimento."'")." 
                               ,'$this->ac10_hora' 
                               ,'$this->ac10_obs' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Acordo Movimentação ($this->ac10_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Acordo Movimentação já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Acordo Movimentação ($this->ac10_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ac10_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->ac10_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,16145,'$this->ac10_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,2832,16145,'','".AddSlashes(pg_result($resaco,0,'ac10_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2832,16146,'','".AddSlashes(pg_result($resaco,0,'ac10_acordomovimentacaotipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2832,16147,'','".AddSlashes(pg_result($resaco,0,'ac10_acordo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2832,16148,'','".AddSlashes(pg_result($resaco,0,'ac10_id_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2832,16149,'','".AddSlashes(pg_result($resaco,0,'ac10_datamovimento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2832,16150,'','".AddSlashes(pg_result($resaco,0,'ac10_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2832,16151,'','".AddSlashes(pg_result($resaco,0,'ac10_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($ac10_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update acordomovimentacao set ";
     $virgula = "";
     if(trim($this->ac10_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ac10_sequencial"])){ 
       $sql  .= $virgula." ac10_sequencial = $this->ac10_sequencial ";
       $virgula = ",";
       if(trim($this->ac10_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "ac10_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ac10_acordomovimentacaotipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ac10_acordomovimentacaotipo"])){ 
       $sql  .= $virgula." ac10_acordomovimentacaotipo = $this->ac10_acordomovimentacaotipo ";
       $virgula = ",";
       if(trim($this->ac10_acordomovimentacaotipo) == null ){ 
         $this->erro_sql = " Campo Acordo Movimentação Tipo nao Informado.";
         $this->erro_campo = "ac10_acordomovimentacaotipo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ac10_acordo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ac10_acordo"])){ 
       $sql  .= $virgula." ac10_acordo = $this->ac10_acordo ";
       $virgula = ",";
       if(trim($this->ac10_acordo) == null ){ 
         $this->erro_sql = " Campo Acordo nao Informado.";
         $this->erro_campo = "ac10_acordo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ac10_id_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ac10_id_usuario"])){ 
       $sql  .= $virgula." ac10_id_usuario = $this->ac10_id_usuario ";
       $virgula = ",";
       if(trim($this->ac10_id_usuario) == null ){ 
         $this->erro_sql = " Campo Código Usúario nao Informado.";
         $this->erro_campo = "ac10_id_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ac10_datamovimento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ac10_datamovimento_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["ac10_datamovimento_dia"] !="") ){ 
       $sql  .= $virgula." ac10_datamovimento = '$this->ac10_datamovimento' ";
       $virgula = ",";
       if(trim($this->ac10_datamovimento) == null ){ 
         $this->erro_sql = " Campo Data Movimentação nao Informado.";
         $this->erro_campo = "ac10_datamovimento_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["ac10_datamovimento_dia"])){ 
         $sql  .= $virgula." ac10_datamovimento = null ";
         $virgula = ",";
         if(trim($this->ac10_datamovimento) == null ){ 
           $this->erro_sql = " Campo Data Movimentação nao Informado.";
           $this->erro_campo = "ac10_datamovimento_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->ac10_hora)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ac10_hora"])){ 
       $sql  .= $virgula." ac10_hora = '$this->ac10_hora' ";
       $virgula = ",";
       if(trim($this->ac10_hora) == null ){ 
         $this->erro_sql = " Campo Hora nao Informado.";
         $this->erro_campo = "ac10_hora";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ac10_obs)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ac10_obs"])){ 
       $sql  .= $virgula." ac10_obs = '$this->ac10_obs' ";
       $virgula = ",";
       if(trim($this->ac10_obs) == null ){ 
         $this->erro_sql = " Campo Observação nao Informado.";
         $this->erro_campo = "ac10_obs";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($ac10_sequencial!=null){
       $sql .= " ac10_sequencial = $this->ac10_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->ac10_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,16145,'$this->ac10_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ac10_sequencial"]) || $this->ac10_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,2832,16145,'".AddSlashes(pg_result($resaco,$conresaco,'ac10_sequencial'))."','$this->ac10_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ac10_acordomovimentacaotipo"]) || $this->ac10_acordomovimentacaotipo != "")
           $resac = db_query("insert into db_acount values($acount,2832,16146,'".AddSlashes(pg_result($resaco,$conresaco,'ac10_acordomovimentacaotipo'))."','$this->ac10_acordomovimentacaotipo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ac10_acordo"]) || $this->ac10_acordo != "")
           $resac = db_query("insert into db_acount values($acount,2832,16147,'".AddSlashes(pg_result($resaco,$conresaco,'ac10_acordo'))."','$this->ac10_acordo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ac10_id_usuario"]) || $this->ac10_id_usuario != "")
           $resac = db_query("insert into db_acount values($acount,2832,16148,'".AddSlashes(pg_result($resaco,$conresaco,'ac10_id_usuario'))."','$this->ac10_id_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ac10_datamovimento"]) || $this->ac10_datamovimento != "")
           $resac = db_query("insert into db_acount values($acount,2832,16149,'".AddSlashes(pg_result($resaco,$conresaco,'ac10_datamovimento'))."','$this->ac10_datamovimento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ac10_hora"]) || $this->ac10_hora != "")
           $resac = db_query("insert into db_acount values($acount,2832,16150,'".AddSlashes(pg_result($resaco,$conresaco,'ac10_hora'))."','$this->ac10_hora',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ac10_obs"]) || $this->ac10_obs != "")
           $resac = db_query("insert into db_acount values($acount,2832,16151,'".AddSlashes(pg_result($resaco,$conresaco,'ac10_obs'))."','$this->ac10_obs',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Acordo Movimentação nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ac10_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Acordo Movimentação nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ac10_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ac10_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($ac10_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($ac10_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,16145,'$ac10_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,2832,16145,'','".AddSlashes(pg_result($resaco,$iresaco,'ac10_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2832,16146,'','".AddSlashes(pg_result($resaco,$iresaco,'ac10_acordomovimentacaotipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2832,16147,'','".AddSlashes(pg_result($resaco,$iresaco,'ac10_acordo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2832,16148,'','".AddSlashes(pg_result($resaco,$iresaco,'ac10_id_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2832,16149,'','".AddSlashes(pg_result($resaco,$iresaco,'ac10_datamovimento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2832,16150,'','".AddSlashes(pg_result($resaco,$iresaco,'ac10_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2832,16151,'','".AddSlashes(pg_result($resaco,$iresaco,'ac10_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from acordomovimentacao
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ac10_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ac10_sequencial = $ac10_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Acordo Movimentação nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ac10_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Acordo Movimentação nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ac10_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ac10_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:acordomovimentacao";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $ac10_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     
     $sql .= " from acordomovimentacao ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = acordomovimentacao.ac10_id_usuario";
     $sql .= "      inner join acordomovimentacaotipo  on  acordomovimentacaotipo.ac09_sequencial = acordomovimentacao.ac10_acordomovimentacaotipo";
     $sql .= "      inner join acordo  on  acordo.ac16_sequencial = acordomovimentacao.ac10_acordo";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = acordo.ac16_coddepto";
     $sql .= "      inner join acordosituacao  on  acordosituacao.ac17_sequencial = acordo.ac16_acordosituacao";
     $sql2 = "";
     
     
     
     if($dbwhere==""){
       if($ac10_sequencial!=null ){
         $sql2 .= " where acordomovimentacao.ac10_sequencial = $ac10_sequencial "; 
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
   function sql_query_file ( $ac10_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from acordomovimentacao ";
     $sql2 = "";
     if($dbwhere==""){
       if($ac10_sequencial!=null ){
         $sql2 .= " where acordomovimentacao.ac10_sequencial = $ac10_sequencial "; 
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
  
function sql_query_verificacancelado ( $ac10_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     
     $sql .= " from acordomovimentacao ";
     $sql .= "      inner join acordomovimentacaotipo  on  acordomovimentacaotipo.ac09_sequencial = acordomovimentacao.ac10_acordomovimentacaotipo";
     $sql .= "      inner join acordo  on  acordo.ac16_sequencial = acordomovimentacao.ac10_acordo";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = acordo.ac16_coddepto";
     $sql .= "      inner join acordosituacao  on  acordosituacao.ac17_sequencial = acordo.ac16_acordosituacao";
     $sql .= "      left  join acordomovimentacaocancela on ac25_acordomovimentacaocancela = ac10_sequencial ";
     $sql2 = "";
     if($dbwhere==""){
       if($ac10_sequencial!=null ){
         $sql2 .= " where acordomovimentacao.ac10_sequencial = $ac10_sequencial "; 
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
  
   function sql_query_acertaracordo ( $ac10_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     
     $sql .= " from acordomovimentacao ";
     $sql .= "      inner join acordomovimentacaotipo on ac09_sequencial = ac10_acordomovimentacaotipo ";
     $sql2 = "";
     if($dbwhere==""){
       if($ac10_sequencial!=null ){
         $sql2 .= " where acordomovimentacao.ac10_sequencial = $ac10_sequencial "; 
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