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

//MODULO: Custos
//CLASSE DA ENTIDADE custoliberaplanilhamovimentos
class cl_custoliberaplanilhamovimentos { 
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
   var $cc16_sequencial = 0; 
   var $cc16_custoplanilha = 0; 
   var $cc16_id_usuario = 0; 
   var $cc16_datamov_dia = null; 
   var $cc16_datamov_mes = null; 
   var $cc16_datamov_ano = null; 
   var $cc16_datamov = null; 
   var $cc16_hora = null; 
   var $cc16_motivo = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 cc16_sequencial = int4 = Sequencial 
                 cc16_custoplanilha = int4 = Custo Planilha 
                 cc16_id_usuario = int4 = Usuário 
                 cc16_datamov = date = Data 
                 cc16_hora = char(5) = Hora 
                 cc16_motivo = text = Motivo 
                 ";
   //funcao construtor da classe 
   function cl_custoliberaplanilhamovimentos() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("custoliberaplanilhamovimentos"); 
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
       $this->cc16_sequencial = ($this->cc16_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["cc16_sequencial"]:$this->cc16_sequencial);
       $this->cc16_custoplanilha = ($this->cc16_custoplanilha == ""?@$GLOBALS["HTTP_POST_VARS"]["cc16_custoplanilha"]:$this->cc16_custoplanilha);
       $this->cc16_id_usuario = ($this->cc16_id_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["cc16_id_usuario"]:$this->cc16_id_usuario);
       if($this->cc16_datamov == ""){
         $this->cc16_datamov_dia = ($this->cc16_datamov_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["cc16_datamov_dia"]:$this->cc16_datamov_dia);
         $this->cc16_datamov_mes = ($this->cc16_datamov_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["cc16_datamov_mes"]:$this->cc16_datamov_mes);
         $this->cc16_datamov_ano = ($this->cc16_datamov_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["cc16_datamov_ano"]:$this->cc16_datamov_ano);
         if($this->cc16_datamov_dia != ""){
            $this->cc16_datamov = $this->cc16_datamov_ano."-".$this->cc16_datamov_mes."-".$this->cc16_datamov_dia;
         }
       }
       $this->cc16_hora = ($this->cc16_hora == ""?@$GLOBALS["HTTP_POST_VARS"]["cc16_hora"]:$this->cc16_hora);
       $this->cc16_motivo = ($this->cc16_motivo == ""?@$GLOBALS["HTTP_POST_VARS"]["cc16_motivo"]:$this->cc16_motivo);
     }else{
       $this->cc16_sequencial = ($this->cc16_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["cc16_sequencial"]:$this->cc16_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($cc16_sequencial){ 
      $this->atualizacampos();
     if($this->cc16_custoplanilha == null ){ 
       $this->erro_sql = " Campo Custo Planilha nao Informado.";
       $this->erro_campo = "cc16_custoplanilha";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->cc16_id_usuario == null ){ 
       $this->erro_sql = " Campo Usuário nao Informado.";
       $this->erro_campo = "cc16_id_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->cc16_datamov == null ){ 
       $this->erro_sql = " Campo Data nao Informado.";
       $this->erro_campo = "cc16_datamov_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->cc16_hora == null ){ 
       $this->erro_sql = " Campo Hora nao Informado.";
       $this->erro_campo = "cc16_hora";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->cc16_motivo == null ){ 
       $this->erro_sql = " Campo Motivo nao Informado.";
       $this->erro_campo = "cc16_motivo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($cc16_sequencial == "" || $cc16_sequencial == null ){
       $result = db_query("select nextval('custoliberaplanilhamovimentos_cc16_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: custoliberaplanilhamovimentos_cc16_sequencial_seq do campo: cc16_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->cc16_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from custoliberaplanilhamovimentos_cc16_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $cc16_sequencial)){
         $this->erro_sql = " Campo cc16_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->cc16_sequencial = $cc16_sequencial; 
       }
     }
     if(($this->cc16_sequencial == null) || ($this->cc16_sequencial == "") ){ 
       $this->erro_sql = " Campo cc16_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into custoliberaplanilhamovimentos(
                                       cc16_sequencial 
                                      ,cc16_custoplanilha 
                                      ,cc16_id_usuario 
                                      ,cc16_datamov 
                                      ,cc16_hora 
                                      ,cc16_motivo 
                       )
                values (
                                $this->cc16_sequencial 
                               ,$this->cc16_custoplanilha 
                               ,$this->cc16_id_usuario 
                               ,".($this->cc16_datamov == "null" || $this->cc16_datamov == ""?"null":"'".$this->cc16_datamov."'")." 
                               ,'$this->cc16_hora' 
                               ,'$this->cc16_motivo' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Custo Libera Planilha Movimentos ($this->cc16_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Custo Libera Planilha Movimentos já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Custo Libera Planilha Movimentos ($this->cc16_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->cc16_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->cc16_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,15120,'$this->cc16_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,2661,15120,'','".AddSlashes(pg_result($resaco,0,'cc16_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2661,15121,'','".AddSlashes(pg_result($resaco,0,'cc16_custoplanilha'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2661,15122,'','".AddSlashes(pg_result($resaco,0,'cc16_id_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2661,15123,'','".AddSlashes(pg_result($resaco,0,'cc16_datamov'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2661,15124,'','".AddSlashes(pg_result($resaco,0,'cc16_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2661,15125,'','".AddSlashes(pg_result($resaco,0,'cc16_motivo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($cc16_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update custoliberaplanilhamovimentos set ";
     $virgula = "";
     if(trim($this->cc16_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cc16_sequencial"])){ 
       $sql  .= $virgula." cc16_sequencial = $this->cc16_sequencial ";
       $virgula = ",";
       if(trim($this->cc16_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "cc16_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->cc16_custoplanilha)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cc16_custoplanilha"])){ 
       $sql  .= $virgula." cc16_custoplanilha = $this->cc16_custoplanilha ";
       $virgula = ",";
       if(trim($this->cc16_custoplanilha) == null ){ 
         $this->erro_sql = " Campo Custo Planilha nao Informado.";
         $this->erro_campo = "cc16_custoplanilha";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->cc16_id_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cc16_id_usuario"])){ 
       $sql  .= $virgula." cc16_id_usuario = $this->cc16_id_usuario ";
       $virgula = ",";
       if(trim($this->cc16_id_usuario) == null ){ 
         $this->erro_sql = " Campo Usuário nao Informado.";
         $this->erro_campo = "cc16_id_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->cc16_datamov)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cc16_datamov_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["cc16_datamov_dia"] !="") ){ 
       $sql  .= $virgula." cc16_datamov = '$this->cc16_datamov' ";
       $virgula = ",";
       if(trim($this->cc16_datamov) == null ){ 
         $this->erro_sql = " Campo Data nao Informado.";
         $this->erro_campo = "cc16_datamov_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["cc16_datamov_dia"])){ 
         $sql  .= $virgula." cc16_datamov = null ";
         $virgula = ",";
         if(trim($this->cc16_datamov) == null ){ 
           $this->erro_sql = " Campo Data nao Informado.";
           $this->erro_campo = "cc16_datamov_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->cc16_hora)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cc16_hora"])){ 
       $sql  .= $virgula." cc16_hora = '$this->cc16_hora' ";
       $virgula = ",";
       if(trim($this->cc16_hora) == null ){ 
         $this->erro_sql = " Campo Hora nao Informado.";
         $this->erro_campo = "cc16_hora";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->cc16_motivo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cc16_motivo"])){ 
       $sql  .= $virgula." cc16_motivo = '$this->cc16_motivo' ";
       $virgula = ",";
       if(trim($this->cc16_motivo) == null ){ 
         $this->erro_sql = " Campo Motivo nao Informado.";
         $this->erro_campo = "cc16_motivo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($cc16_sequencial!=null){
       $sql .= " cc16_sequencial = $this->cc16_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->cc16_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,15120,'$this->cc16_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cc16_sequencial"]) || $this->cc16_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,2661,15120,'".AddSlashes(pg_result($resaco,$conresaco,'cc16_sequencial'))."','$this->cc16_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cc16_custoplanilha"]) || $this->cc16_custoplanilha != "")
           $resac = db_query("insert into db_acount values($acount,2661,15121,'".AddSlashes(pg_result($resaco,$conresaco,'cc16_custoplanilha'))."','$this->cc16_custoplanilha',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cc16_id_usuario"]) || $this->cc16_id_usuario != "")
           $resac = db_query("insert into db_acount values($acount,2661,15122,'".AddSlashes(pg_result($resaco,$conresaco,'cc16_id_usuario'))."','$this->cc16_id_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cc16_datamov"]) || $this->cc16_datamov != "")
           $resac = db_query("insert into db_acount values($acount,2661,15123,'".AddSlashes(pg_result($resaco,$conresaco,'cc16_datamov'))."','$this->cc16_datamov',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cc16_hora"]) || $this->cc16_hora != "")
           $resac = db_query("insert into db_acount values($acount,2661,15124,'".AddSlashes(pg_result($resaco,$conresaco,'cc16_hora'))."','$this->cc16_hora',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cc16_motivo"]) || $this->cc16_motivo != "")
           $resac = db_query("insert into db_acount values($acount,2661,15125,'".AddSlashes(pg_result($resaco,$conresaco,'cc16_motivo'))."','$this->cc16_motivo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Custo Libera Planilha Movimentos nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->cc16_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Custo Libera Planilha Movimentos nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->cc16_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->cc16_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($cc16_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($cc16_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,15120,'$cc16_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,2661,15120,'','".AddSlashes(pg_result($resaco,$iresaco,'cc16_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2661,15121,'','".AddSlashes(pg_result($resaco,$iresaco,'cc16_custoplanilha'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2661,15122,'','".AddSlashes(pg_result($resaco,$iresaco,'cc16_id_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2661,15123,'','".AddSlashes(pg_result($resaco,$iresaco,'cc16_datamov'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2661,15124,'','".AddSlashes(pg_result($resaco,$iresaco,'cc16_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2661,15125,'','".AddSlashes(pg_result($resaco,$iresaco,'cc16_motivo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from custoliberaplanilhamovimentos
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($cc16_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " cc16_sequencial = $cc16_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Custo Libera Planilha Movimentos nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$cc16_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Custo Libera Planilha Movimentos nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$cc16_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$cc16_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:custoliberaplanilhamovimentos";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $cc16_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from custoliberaplanilhamovimentos ";
     $sql .= "      inner join custoplanilha  on  custoplanilha.cc15_sequencial = custoliberaplanilhamovimentos.cc16_custoplanilha";
     $sql2 = "";
     if($dbwhere==""){
       if($cc16_sequencial!=null ){
         $sql2 .= " where custoliberaplanilhamovimentos.cc16_sequencial = $cc16_sequencial "; 
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
   function sql_query_file ( $cc16_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from custoliberaplanilhamovimentos ";
     $sql2 = "";
     if($dbwhere==""){
       if($cc16_sequencial!=null ){
         $sql2 .= " where custoliberaplanilhamovimentos.cc16_sequencial = $cc16_sequencial "; 
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