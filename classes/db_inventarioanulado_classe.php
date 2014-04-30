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

//MODULO: patrimonio
//CLASSE DA ENTIDADE inventarioanulado
class cl_inventarioanulado { 
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
   var $t76_sequencial = 0; 
   var $t76_inventario = 0; 
   var $t76_dataanulacao_dia = null; 
   var $t76_dataanulacao_mes = null; 
   var $t76_dataanulacao_ano = null; 
   var $t76_dataanulacao = null; 
   var $t76_horaanulacao = null; 
   var $t76_usuario = 0; 
   var $t76_motivo = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 t76_sequencial = int4 = Sequencial de inventários anulados 
                 t76_inventario = int4 = Inventario 
                 t76_dataanulacao = date = Data da anulação do inventario 
                 t76_horaanulacao = varchar(20) = Hora de anulação de um inventário 
                 t76_usuario = int4 = Usuario 
                 t76_motivo = text = Motivo da anulação 
                 ";
   //funcao construtor da classe 
   function cl_inventarioanulado() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("inventarioanulado"); 
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
       $this->t76_sequencial = ($this->t76_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["t76_sequencial"]:$this->t76_sequencial);
       $this->t76_inventario = ($this->t76_inventario == ""?@$GLOBALS["HTTP_POST_VARS"]["t76_inventario"]:$this->t76_inventario);
       if($this->t76_dataanulacao == ""){
         $this->t76_dataanulacao_dia = ($this->t76_dataanulacao_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["t76_dataanulacao_dia"]:$this->t76_dataanulacao_dia);
         $this->t76_dataanulacao_mes = ($this->t76_dataanulacao_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["t76_dataanulacao_mes"]:$this->t76_dataanulacao_mes);
         $this->t76_dataanulacao_ano = ($this->t76_dataanulacao_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["t76_dataanulacao_ano"]:$this->t76_dataanulacao_ano);
         if($this->t76_dataanulacao_dia != ""){
            $this->t76_dataanulacao = $this->t76_dataanulacao_ano."-".$this->t76_dataanulacao_mes."-".$this->t76_dataanulacao_dia;
         }
       }
       $this->t76_horaanulacao = ($this->t76_horaanulacao == ""?@$GLOBALS["HTTP_POST_VARS"]["t76_horaanulacao"]:$this->t76_horaanulacao);
       $this->t76_usuario = ($this->t76_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["t76_usuario"]:$this->t76_usuario);
       $this->t76_motivo = ($this->t76_motivo == ""?@$GLOBALS["HTTP_POST_VARS"]["t76_motivo"]:$this->t76_motivo);
     }else{
       $this->t76_sequencial = ($this->t76_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["t76_sequencial"]:$this->t76_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($t76_sequencial){ 
      $this->atualizacampos();
     if($this->t76_inventario == null ){ 
       $this->erro_sql = " Campo Inventario nao Informado.";
       $this->erro_campo = "t76_inventario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->t76_dataanulacao == null ){ 
       $this->erro_sql = " Campo Data da anulação do inventario nao Informado.";
       $this->erro_campo = "t76_dataanulacao_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->t76_horaanulacao == null ){ 
       $this->erro_sql = " Campo Hora de anulação de um inventário nao Informado.";
       $this->erro_campo = "t76_horaanulacao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->t76_usuario == null ){ 
       $this->erro_sql = " Campo Usuario nao Informado.";
       $this->erro_campo = "t76_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->t76_motivo == null ){ 
       $this->erro_sql = " Campo Motivo da anulação nao Informado.";
       $this->erro_campo = "t76_motivo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($t76_sequencial == "" || $t76_sequencial == null ){
       $result = db_query("select nextval('inventarioanulado_t76_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: inventarioanulado_t76_sequencial_seq do campo: t76_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->t76_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from inventarioanulado_t76_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $t76_sequencial)){
         $this->erro_sql = " Campo t76_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->t76_sequencial = $t76_sequencial; 
       }
     }
     if(($this->t76_sequencial == null) || ($this->t76_sequencial == "") ){ 
       $this->erro_sql = " Campo t76_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into inventarioanulado(
                                       t76_sequencial 
                                      ,t76_inventario 
                                      ,t76_dataanulacao 
                                      ,t76_horaanulacao 
                                      ,t76_usuario 
                                      ,t76_motivo 
                       )
                values (
                                $this->t76_sequencial 
                               ,$this->t76_inventario 
                               ,".($this->t76_dataanulacao == "null" || $this->t76_dataanulacao == ""?"null":"'".$this->t76_dataanulacao."'")." 
                               ,'$this->t76_horaanulacao' 
                               ,$this->t76_usuario 
                               ,'$this->t76_motivo' 
                      )";
     //echo $sql; die();
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Inventários anulados ($this->t76_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Inventários anulados já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Inventários anulados ($this->t76_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->t76_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->t76_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,19334,'$this->t76_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,3437,19334,'','".AddSlashes(pg_result($resaco,0,'t76_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3437,19335,'','".AddSlashes(pg_result($resaco,0,'t76_inventario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3437,19336,'','".AddSlashes(pg_result($resaco,0,'t76_dataanulacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3437,19337,'','".AddSlashes(pg_result($resaco,0,'t76_horaanulacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3437,19345,'','".AddSlashes(pg_result($resaco,0,'t76_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3437,19347,'','".AddSlashes(pg_result($resaco,0,'t76_motivo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($t76_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update inventarioanulado set ";
     $virgula = "";
     if(trim($this->t76_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t76_sequencial"])){ 
       $sql  .= $virgula." t76_sequencial = $this->t76_sequencial ";
       $virgula = ",";
       if(trim($this->t76_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial de inventários anulados nao Informado.";
         $this->erro_campo = "t76_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->t76_inventario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t76_inventario"])){ 
       $sql  .= $virgula." t76_inventario = $this->t76_inventario ";
       $virgula = ",";
       if(trim($this->t76_inventario) == null ){ 
         $this->erro_sql = " Campo Inventario nao Informado.";
         $this->erro_campo = "t76_inventario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->t76_dataanulacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t76_dataanulacao_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["t76_dataanulacao_dia"] !="") ){ 
       $sql  .= $virgula." t76_dataanulacao = '$this->t76_dataanulacao' ";
       $virgula = ",";
       if(trim($this->t76_dataanulacao) == null ){ 
         $this->erro_sql = " Campo Data da anulação do inventario nao Informado.";
         $this->erro_campo = "t76_dataanulacao_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["t76_dataanulacao_dia"])){ 
         $sql  .= $virgula." t76_dataanulacao = null ";
         $virgula = ",";
         if(trim($this->t76_dataanulacao) == null ){ 
           $this->erro_sql = " Campo Data da anulação do inventario nao Informado.";
           $this->erro_campo = "t76_dataanulacao_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->t76_horaanulacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t76_horaanulacao"])){ 
       $sql  .= $virgula." t76_horaanulacao = '$this->t76_horaanulacao' ";
       $virgula = ",";
       if(trim($this->t76_horaanulacao) == null ){ 
         $this->erro_sql = " Campo Hora de anulação de um inventário nao Informado.";
         $this->erro_campo = "t76_horaanulacao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->t76_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t76_usuario"])){ 
       $sql  .= $virgula." t76_usuario = $this->t76_usuario ";
       $virgula = ",";
       if(trim($this->t76_usuario) == null ){ 
         $this->erro_sql = " Campo Usuario nao Informado.";
         $this->erro_campo = "t76_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->t76_motivo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t76_motivo"])){ 
       $sql  .= $virgula." t76_motivo = '$this->t76_motivo' ";
       $virgula = ",";
       if(trim($this->t76_motivo) == null ){ 
         $this->erro_sql = " Campo Motivo da anulação nao Informado.";
         $this->erro_campo = "t76_motivo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($t76_sequencial!=null){
       $sql .= " t76_sequencial = $this->t76_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->t76_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,19334,'$this->t76_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["t76_sequencial"]) || $this->t76_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,3437,19334,'".AddSlashes(pg_result($resaco,$conresaco,'t76_sequencial'))."','$this->t76_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["t76_inventario"]) || $this->t76_inventario != "")
           $resac = db_query("insert into db_acount values($acount,3437,19335,'".AddSlashes(pg_result($resaco,$conresaco,'t76_inventario'))."','$this->t76_inventario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["t76_dataanulacao"]) || $this->t76_dataanulacao != "")
           $resac = db_query("insert into db_acount values($acount,3437,19336,'".AddSlashes(pg_result($resaco,$conresaco,'t76_dataanulacao'))."','$this->t76_dataanulacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["t76_horaanulacao"]) || $this->t76_horaanulacao != "")
           $resac = db_query("insert into db_acount values($acount,3437,19337,'".AddSlashes(pg_result($resaco,$conresaco,'t76_horaanulacao'))."','$this->t76_horaanulacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["t76_usuario"]) || $this->t76_usuario != "")
           $resac = db_query("insert into db_acount values($acount,3437,19345,'".AddSlashes(pg_result($resaco,$conresaco,'t76_usuario'))."','$this->t76_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["t76_motivo"]) || $this->t76_motivo != "")
           $resac = db_query("insert into db_acount values($acount,3437,19347,'".AddSlashes(pg_result($resaco,$conresaco,'t76_motivo'))."','$this->t76_motivo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Inventários anulados nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->t76_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Inventários anulados nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->t76_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->t76_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($t76_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($t76_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,19334,'$t76_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,3437,19334,'','".AddSlashes(pg_result($resaco,$iresaco,'t76_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3437,19335,'','".AddSlashes(pg_result($resaco,$iresaco,'t76_inventario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3437,19336,'','".AddSlashes(pg_result($resaco,$iresaco,'t76_dataanulacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3437,19337,'','".AddSlashes(pg_result($resaco,$iresaco,'t76_horaanulacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3437,19345,'','".AddSlashes(pg_result($resaco,$iresaco,'t76_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3437,19347,'','".AddSlashes(pg_result($resaco,$iresaco,'t76_motivo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from inventarioanulado
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($t76_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " t76_sequencial = $t76_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Inventários anulados nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$t76_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Inventários anulados nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$t76_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$t76_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:inventarioanulado";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $t76_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from inventarioanulado ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = inventarioanulado.t76_usuario";
     $sql .= "      inner join inventario  on  inventario.t75_sequencial = inventarioanulado.t76_inventario";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = inventario.t75_db_depart";
     $sql .= "      left  join protprocesso  on  protprocesso.p58_codproc = inventario.t75_processo";
     $sql .= "      left  join acordocomissao  on  acordocomissao.ac08_sequencial = inventario.t75_acordocomissao";
     $sql2 = "";
     if($dbwhere==""){
       if($t76_sequencial!=null ){
         $sql2 .= " where inventarioanulado.t76_sequencial = $t76_sequencial "; 
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
   function sql_query_file ( $t76_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from inventarioanulado ";
     $sql2 = "";
     if($dbwhere==""){
       if($t76_sequencial!=null ){
         $sql2 .= " where inventarioanulado.t76_sequencial = $t76_sequencial "; 
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