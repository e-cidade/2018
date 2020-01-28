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

//MODULO: recursoshumanos
//CLASSE DA ENTIDADE rhpromocao
class cl_rhpromocao { 
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
   var $h72_sequencial = 0; 
   var $h72_regist = 0; 
   var $h72_dtinicial_dia = null; 
   var $h72_dtinicial_mes = null; 
   var $h72_dtinicial_ano = null; 
   var $h72_dtinicial = null; 
   var $h72_dtfinal_dia = null; 
   var $h72_dtfinal_mes = null; 
   var $h72_dtfinal_ano = null; 
   var $h72_dtfinal = null; 
   var $h72_ativo = 'f'; 
   var $h72_observacao = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 h72_sequencial = int4 = sequencial 
                 h72_regist = int4 = Matricula do servidor 
                 h72_dtinicial = date = Data inicial 
                 h72_dtfinal = date = Data final 
                 h72_ativo = bool = Ativo 
                 h72_observacao = text = Observação 
                 ";
   //funcao construtor da classe 
   function cl_rhpromocao() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("rhpromocao"); 
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
       $this->h72_sequencial = ($this->h72_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["h72_sequencial"]:$this->h72_sequencial);
       $this->h72_regist = ($this->h72_regist == ""?@$GLOBALS["HTTP_POST_VARS"]["h72_regist"]:$this->h72_regist);
       if($this->h72_dtinicial == ""){
         $this->h72_dtinicial_dia = ($this->h72_dtinicial_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["h72_dtinicial_dia"]:$this->h72_dtinicial_dia);
         $this->h72_dtinicial_mes = ($this->h72_dtinicial_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["h72_dtinicial_mes"]:$this->h72_dtinicial_mes);
         $this->h72_dtinicial_ano = ($this->h72_dtinicial_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["h72_dtinicial_ano"]:$this->h72_dtinicial_ano);
         if($this->h72_dtinicial_dia != ""){
            $this->h72_dtinicial = $this->h72_dtinicial_ano."-".$this->h72_dtinicial_mes."-".$this->h72_dtinicial_dia;
         }
       }
       if($this->h72_dtfinal == ""){
         $this->h72_dtfinal_dia = ($this->h72_dtfinal_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["h72_dtfinal_dia"]:$this->h72_dtfinal_dia);
         $this->h72_dtfinal_mes = ($this->h72_dtfinal_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["h72_dtfinal_mes"]:$this->h72_dtfinal_mes);
         $this->h72_dtfinal_ano = ($this->h72_dtfinal_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["h72_dtfinal_ano"]:$this->h72_dtfinal_ano);
         if($this->h72_dtfinal_dia != ""){
            $this->h72_dtfinal = $this->h72_dtfinal_ano."-".$this->h72_dtfinal_mes."-".$this->h72_dtfinal_dia;
         }
       }
       $this->h72_ativo = ($this->h72_ativo == "f"?@$GLOBALS["HTTP_POST_VARS"]["h72_ativo"]:$this->h72_ativo);
       $this->h72_observacao = ($this->h72_observacao == ""?@$GLOBALS["HTTP_POST_VARS"]["h72_observacao"]:$this->h72_observacao);
     }else{
       $this->h72_sequencial = ($this->h72_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["h72_sequencial"]:$this->h72_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($h72_sequencial){ 
      $this->atualizacampos();
     if($this->h72_regist == null ){ 
       $this->erro_sql = " Campo Matricula do servidor nao Informado.";
       $this->erro_campo = "h72_regist";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->h72_dtinicial == null ){ 
       $this->erro_sql = " Campo Data inicial nao Informado.";
       $this->erro_campo = "h72_dtinicial_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }

     if($this->h72_ativo == null ){ 
       $this->erro_sql = " Campo Ativo nao Informado.";
       $this->erro_campo = "h72_ativo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($h72_sequencial == "" || $h72_sequencial == null ){
       $result = db_query("select nextval('rhpromocao_h72_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: rhpromocao_h72_sequencial_seq do campo: h72_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->h72_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from rhpromocao_h72_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $h72_sequencial)){
         $this->erro_sql = " Campo h72_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->h72_sequencial = $h72_sequencial; 
       }
     }
     if(($this->h72_sequencial == null) || ($this->h72_sequencial == "") ){ 
       $this->erro_sql = " Campo h72_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into rhpromocao(
                                       h72_sequencial 
                                      ,h72_regist 
                                      ,h72_dtinicial 
                                      ,h72_dtfinal 
                                      ,h72_ativo 
                                      ,h72_observacao 
                       )
                values (
                                $this->h72_sequencial 
                               ,$this->h72_regist 
                               ,".($this->h72_dtinicial == "null" || $this->h72_dtinicial == ""?"null":"'".$this->h72_dtinicial."'")." 
                               ,".($this->h72_dtfinal == "null" || $this->h72_dtfinal == ""?"null":"'".$this->h72_dtfinal."'")." 
                               ,'$this->h72_ativo' 
                               ,'$this->h72_observacao' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Promoção ($this->h72_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Promoção já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Promoção ($this->h72_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->h72_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->h72_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,18709,'$this->h72_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,3315,18709,'','".AddSlashes(pg_result($resaco,0,'h72_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3315,18710,'','".AddSlashes(pg_result($resaco,0,'h72_regist'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3315,18711,'','".AddSlashes(pg_result($resaco,0,'h72_dtinicial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3315,18712,'','".AddSlashes(pg_result($resaco,0,'h72_dtfinal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3315,18713,'','".AddSlashes(pg_result($resaco,0,'h72_ativo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3315,18714,'','".AddSlashes(pg_result($resaco,0,'h72_observacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($h72_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update rhpromocao set ";
     $virgula = "";
     if(trim($this->h72_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h72_sequencial"])){ 
       $sql  .= $virgula." h72_sequencial = $this->h72_sequencial ";
       $virgula = ",";
       if(trim($this->h72_sequencial) == null ){ 
         $this->erro_sql = " Campo sequencial nao Informado.";
         $this->erro_campo = "h72_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->h72_regist)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h72_regist"])){ 
       $sql  .= $virgula." h72_regist = $this->h72_regist ";
       $virgula = ",";
       if(trim($this->h72_regist) == null ){ 
         $this->erro_sql = " Campo Matricula do servidor nao Informado.";
         $this->erro_campo = "h72_regist";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->h72_dtinicial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h72_dtinicial_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["h72_dtinicial_dia"] !="") ){ 
       $sql  .= $virgula." h72_dtinicial = '$this->h72_dtinicial' ";
       $virgula = ",";
       if(trim($this->h72_dtinicial) == null ){ 
         $this->erro_sql = " Campo Data inicial nao Informado.";
         $this->erro_campo = "h72_dtinicial_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["h72_dtinicial_dia"])){ 
         $sql  .= $virgula." h72_dtinicial = null ";
         $virgula = ",";
         if(trim($this->h72_dtinicial) == null ){ 
           $this->erro_sql = " Campo Data inicial nao Informado.";
           $this->erro_campo = "h72_dtinicial_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->h72_dtfinal)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h72_dtfinal_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["h72_dtfinal_dia"] !="") ){ 
       $sql  .= $virgula." h72_dtfinal = '$this->h72_dtfinal' ";
       $virgula = ",";
       if(trim($this->h72_dtfinal) == null ){ 
         $this->erro_sql = " Campo Data final nao Informado.";
         $this->erro_campo = "h72_dtfinal_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["h72_dtfinal_dia"])){ 
         $sql  .= $virgula." h72_dtfinal = null ";
         $virgula = ",";
         if(trim($this->h72_dtfinal) == null ){ 
           $this->erro_sql = " Campo Data final nao Informado.";
           $this->erro_campo = "h72_dtfinal_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->h72_ativo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h72_ativo"])){ 
       $sql  .= $virgula." h72_ativo = '$this->h72_ativo' ";
       $virgula = ",";
       if(trim($this->h72_ativo) == null ){ 
         $this->erro_sql = " Campo Ativo nao Informado.";
         $this->erro_campo = "h72_ativo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->h72_observacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h72_observacao"])){ 
       $sql  .= $virgula." h72_observacao = '$this->h72_observacao' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($h72_sequencial!=null){
       $sql .= " h72_sequencial = $this->h72_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->h72_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,18709,'$this->h72_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h72_sequencial"]) || $this->h72_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,3315,18709,'".AddSlashes(pg_result($resaco,$conresaco,'h72_sequencial'))."','$this->h72_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h72_regist"]) || $this->h72_regist != "")
           $resac = db_query("insert into db_acount values($acount,3315,18710,'".AddSlashes(pg_result($resaco,$conresaco,'h72_regist'))."','$this->h72_regist',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h72_dtinicial"]) || $this->h72_dtinicial != "")
           $resac = db_query("insert into db_acount values($acount,3315,18711,'".AddSlashes(pg_result($resaco,$conresaco,'h72_dtinicial'))."','$this->h72_dtinicial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h72_dtfinal"]) || $this->h72_dtfinal != "")
           $resac = db_query("insert into db_acount values($acount,3315,18712,'".AddSlashes(pg_result($resaco,$conresaco,'h72_dtfinal'))."','$this->h72_dtfinal',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h72_ativo"]) || $this->h72_ativo != "")
           $resac = db_query("insert into db_acount values($acount,3315,18713,'".AddSlashes(pg_result($resaco,$conresaco,'h72_ativo'))."','$this->h72_ativo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h72_observacao"]) || $this->h72_observacao != "")
           $resac = db_query("insert into db_acount values($acount,3315,18714,'".AddSlashes(pg_result($resaco,$conresaco,'h72_observacao'))."','$this->h72_observacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
    // echo $sql; die();
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Promoção nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->h72_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Promoção nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->h72_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->h72_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($h72_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($h72_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,18709,'$h72_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,3315,18709,'','".AddSlashes(pg_result($resaco,$iresaco,'h72_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3315,18710,'','".AddSlashes(pg_result($resaco,$iresaco,'h72_regist'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3315,18711,'','".AddSlashes(pg_result($resaco,$iresaco,'h72_dtinicial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3315,18712,'','".AddSlashes(pg_result($resaco,$iresaco,'h72_dtfinal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3315,18713,'','".AddSlashes(pg_result($resaco,$iresaco,'h72_ativo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3315,18714,'','".AddSlashes(pg_result($resaco,$iresaco,'h72_observacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from rhpromocao
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($h72_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " h72_sequencial = $h72_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Promoção nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$h72_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Promoção nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$h72_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$h72_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:rhpromocao";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $h72_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from rhpromocao ";
     $sql .= "      inner join rhpessoal  on  rhpessoal.rh01_regist = rhpromocao.h72_regist";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = rhpessoal.rh01_numcgm";
     $sql .= "      inner join rhestcivil  on  rhestcivil.rh08_estciv = rhpessoal.rh01_estciv";
     $sql .= "      inner join rhraca  on  rhraca.rh18_raca = rhpessoal.rh01_raca";
     $sql .= "      left  join rhfuncao  on  rhfuncao.rh37_funcao = rhpessoal.rh01_funcao and  rhfuncao.rh37_instit = rhpessoal.rh01_instit";
     $sql .= "      inner join rhinstrucao  on  rhinstrucao.rh21_instru = rhpessoal.rh01_instru";
     $sql .= "      inner join rhnacionalidade  on  rhnacionalidade.rh06_nacionalidade = rhpessoal.rh01_nacion";
     $sql2 = "";
     if($dbwhere==""){
       if($h72_sequencial!=null ){
         $sql2 .= " where rhpromocao.h72_sequencial = $h72_sequencial "; 
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
   function sql_query_file ( $h72_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from rhpromocao ";
     $sql2 = "";
     if($dbwhere==""){
       if($h72_sequencial!=null ){
         $sql2 .= " where rhpromocao.h72_sequencial = $h72_sequencial "; 
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
  
  
	/**
	 * Pesquiza promoção pela matricula
	 */
	function sql_query_matricula ( $matricula = null, $campos = "*", $ordem = null, $dbwhere = "" ) {
	
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
	
	  $sql .= " from rhpromocao ";
	  $sql .= "      inner join rhpessoal  on  rhpessoal.rh01_regist = rhpromocao.h72_regist";
	  $sql .= "      inner join cgm  on  cgm.z01_numcgm = rhpessoal.rh01_numcgm";
	  $sql .= "      inner join rhestcivil  on  rhestcivil.rh08_estciv = rhpessoal.rh01_estciv";
	  $sql .= "      inner join rhraca  on  rhraca.rh18_raca = rhpessoal.rh01_raca";
	  $sql .= "      left  join rhfuncao  on  rhfuncao.rh37_funcao = rhpessoal.rh01_funcao and  rhfuncao.rh37_instit = rhpessoal.rh01_instit";
	  $sql .= "      inner join rhinstrucao  on  rhinstrucao.rh21_instru = rhpessoal.rh01_instru";
	  $sql .= "      inner join rhnacionalidade  on  rhnacionalidade.rh06_nacionalidade = rhpessoal.rh01_nacion";
	  $sql2 = "";
	
	  if ($dbwhere == ""){
	    if ($matricula != null ){
	      $sql2 .= " where rhpromocao.h72_regist = $matricula ";
	    }
	  } elseif ($dbwhere != "") {
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