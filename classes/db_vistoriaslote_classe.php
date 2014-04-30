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

//MODULO: fiscal
//CLASSE DA ENTIDADE vistoriaslote
class cl_vistoriaslote { 
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
   var $y06_vistoriaslote = 0; 
   var $y06_data_dia = null; 
   var $y06_data_mes = null; 
   var $y06_data_ano = null; 
   var $y06_data = null; 
   var $y06_hora = null; 
   var $y06_usuario = 0; 
   var $y06_codtipo = 0; 
   var $y06_instit = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 y06_vistoriaslote = int8 = Codigo do lote das vistorias 
                 y06_data = date = Data do lancamento geral 
                 y06_hora = varchar(10) = Hora do lancamento 
                 y06_usuario = int4 = Cod. Usuário 
                 y06_codtipo = int4 = Código do Tipo 
                 y06_instit = int4 = Cod. Instituição 
                 ";
   //funcao construtor da classe 
   function cl_vistoriaslote() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("vistoriaslote"); 
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
       $this->y06_vistoriaslote = ($this->y06_vistoriaslote == ""?@$GLOBALS["HTTP_POST_VARS"]["y06_vistoriaslote"]:$this->y06_vistoriaslote);
       if($this->y06_data == ""){
         $this->y06_data_dia = ($this->y06_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["y06_data_dia"]:$this->y06_data_dia);
         $this->y06_data_mes = ($this->y06_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["y06_data_mes"]:$this->y06_data_mes);
         $this->y06_data_ano = ($this->y06_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["y06_data_ano"]:$this->y06_data_ano);
         if($this->y06_data_dia != ""){
            $this->y06_data = $this->y06_data_ano."-".$this->y06_data_mes."-".$this->y06_data_dia;
         }
       }
       $this->y06_hora = ($this->y06_hora == ""?@$GLOBALS["HTTP_POST_VARS"]["y06_hora"]:$this->y06_hora);
       $this->y06_usuario = ($this->y06_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["y06_usuario"]:$this->y06_usuario);
       $this->y06_codtipo = ($this->y06_codtipo == ""?@$GLOBALS["HTTP_POST_VARS"]["y06_codtipo"]:$this->y06_codtipo);
       $this->y06_instit = ($this->y06_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["y06_instit"]:$this->y06_instit);
     }else{
       $this->y06_vistoriaslote = ($this->y06_vistoriaslote == ""?@$GLOBALS["HTTP_POST_VARS"]["y06_vistoriaslote"]:$this->y06_vistoriaslote);
     }
   }
   // funcao para inclusao
   function incluir ($y06_vistoriaslote){ 
      $this->atualizacampos();
     if($this->y06_data == null ){ 
       $this->erro_sql = " Campo Data do lancamento geral nao Informado.";
       $this->erro_campo = "y06_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->y06_hora == null ){ 
       $this->erro_sql = " Campo Hora do lancamento nao Informado.";
       $this->erro_campo = "y06_hora";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->y06_usuario == null ){ 
       $this->erro_sql = " Campo Cod. Usuário nao Informado.";
       $this->erro_campo = "y06_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->y06_codtipo == null ){ 
       $this->erro_sql = " Campo Código do Tipo nao Informado.";
       $this->erro_campo = "y06_codtipo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->y06_instit == null ){ 
       $this->erro_sql = " Campo Cod. Instituição nao Informado.";
       $this->erro_campo = "y06_instit";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($y06_vistoriaslote == "" || $y06_vistoriaslote == null ){
       $result = db_query("select nextval('vistoriaslote_y06_vistoriaslote_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: vistoriaslote_y06_vistoriaslote_seq do campo: y06_vistoriaslote"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->y06_vistoriaslote = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from vistoriaslote_y06_vistoriaslote_seq");
       if(($result != false) && (pg_result($result,0,0) < $y06_vistoriaslote)){
         $this->erro_sql = " Campo y06_vistoriaslote maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->y06_vistoriaslote = $y06_vistoriaslote; 
       }
     }
     if(($this->y06_vistoriaslote == null) || ($this->y06_vistoriaslote == "") ){ 
       $this->erro_sql = " Campo y06_vistoriaslote nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into vistoriaslote(
                                       y06_vistoriaslote 
                                      ,y06_data 
                                      ,y06_hora 
                                      ,y06_usuario 
                                      ,y06_codtipo 
                                      ,y06_instit 
                       )
                values (
                                $this->y06_vistoriaslote 
                               ,".($this->y06_data == "null" || $this->y06_data == ""?"null":"'".$this->y06_data."'")." 
                               ,'$this->y06_hora' 
                               ,$this->y06_usuario 
                               ,$this->y06_codtipo 
                               ,$this->y06_instit 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Lote das vistorias ($this->y06_vistoriaslote) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Lote das vistorias já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Lote das vistorias ($this->y06_vistoriaslote) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->y06_vistoriaslote;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->y06_vistoriaslote));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,8337,'$this->y06_vistoriaslote','I')");
       $resac = db_query("insert into db_acount values($acount,1408,8337,'','".AddSlashes(pg_result($resaco,0,'y06_vistoriaslote'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1408,8338,'','".AddSlashes(pg_result($resaco,0,'y06_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1408,8339,'','".AddSlashes(pg_result($resaco,0,'y06_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1408,8340,'','".AddSlashes(pg_result($resaco,0,'y06_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1408,8341,'','".AddSlashes(pg_result($resaco,0,'y06_codtipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1408,10663,'','".AddSlashes(pg_result($resaco,0,'y06_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($y06_vistoriaslote=null) { 
      $this->atualizacampos();
     $sql = " update vistoriaslote set ";
     $virgula = "";
     if(trim($this->y06_vistoriaslote)!="" || isset($GLOBALS["HTTP_POST_VARS"]["y06_vistoriaslote"])){ 
       $sql  .= $virgula." y06_vistoriaslote = $this->y06_vistoriaslote ";
       $virgula = ",";
       if(trim($this->y06_vistoriaslote) == null ){ 
         $this->erro_sql = " Campo Codigo do lote das vistorias nao Informado.";
         $this->erro_campo = "y06_vistoriaslote";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->y06_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["y06_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["y06_data_dia"] !="") ){ 
       $sql  .= $virgula." y06_data = '$this->y06_data' ";
       $virgula = ",";
       if(trim($this->y06_data) == null ){ 
         $this->erro_sql = " Campo Data do lancamento geral nao Informado.";
         $this->erro_campo = "y06_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["y06_data_dia"])){ 
         $sql  .= $virgula." y06_data = null ";
         $virgula = ",";
         if(trim($this->y06_data) == null ){ 
           $this->erro_sql = " Campo Data do lancamento geral nao Informado.";
           $this->erro_campo = "y06_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->y06_hora)!="" || isset($GLOBALS["HTTP_POST_VARS"]["y06_hora"])){ 
       $sql  .= $virgula." y06_hora = '$this->y06_hora' ";
       $virgula = ",";
       if(trim($this->y06_hora) == null ){ 
         $this->erro_sql = " Campo Hora do lancamento nao Informado.";
         $this->erro_campo = "y06_hora";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->y06_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["y06_usuario"])){ 
       $sql  .= $virgula." y06_usuario = $this->y06_usuario ";
       $virgula = ",";
       if(trim($this->y06_usuario) == null ){ 
         $this->erro_sql = " Campo Cod. Usuário nao Informado.";
         $this->erro_campo = "y06_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->y06_codtipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["y06_codtipo"])){ 
       $sql  .= $virgula." y06_codtipo = $this->y06_codtipo ";
       $virgula = ",";
       if(trim($this->y06_codtipo) == null ){ 
         $this->erro_sql = " Campo Código do Tipo nao Informado.";
         $this->erro_campo = "y06_codtipo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->y06_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["y06_instit"])){ 
       $sql  .= $virgula." y06_instit = $this->y06_instit ";
       $virgula = ",";
       if(trim($this->y06_instit) == null ){ 
         $this->erro_sql = " Campo Cod. Instituição nao Informado.";
         $this->erro_campo = "y06_instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($y06_vistoriaslote!=null){
       $sql .= " y06_vistoriaslote = $this->y06_vistoriaslote";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->y06_vistoriaslote));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,8337,'$this->y06_vistoriaslote','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["y06_vistoriaslote"]))
           $resac = db_query("insert into db_acount values($acount,1408,8337,'".AddSlashes(pg_result($resaco,$conresaco,'y06_vistoriaslote'))."','$this->y06_vistoriaslote',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["y06_data"]))
           $resac = db_query("insert into db_acount values($acount,1408,8338,'".AddSlashes(pg_result($resaco,$conresaco,'y06_data'))."','$this->y06_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["y06_hora"]))
           $resac = db_query("insert into db_acount values($acount,1408,8339,'".AddSlashes(pg_result($resaco,$conresaco,'y06_hora'))."','$this->y06_hora',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["y06_usuario"]))
           $resac = db_query("insert into db_acount values($acount,1408,8340,'".AddSlashes(pg_result($resaco,$conresaco,'y06_usuario'))."','$this->y06_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["y06_codtipo"]))
           $resac = db_query("insert into db_acount values($acount,1408,8341,'".AddSlashes(pg_result($resaco,$conresaco,'y06_codtipo'))."','$this->y06_codtipo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["y06_instit"]))
           $resac = db_query("insert into db_acount values($acount,1408,10663,'".AddSlashes(pg_result($resaco,$conresaco,'y06_instit'))."','$this->y06_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Lote das vistorias nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->y06_vistoriaslote;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Lote das vistorias nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->y06_vistoriaslote;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->y06_vistoriaslote;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($y06_vistoriaslote=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($y06_vistoriaslote));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,8337,'$y06_vistoriaslote','E')");
         $resac = db_query("insert into db_acount values($acount,1408,8337,'','".AddSlashes(pg_result($resaco,$iresaco,'y06_vistoriaslote'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1408,8338,'','".AddSlashes(pg_result($resaco,$iresaco,'y06_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1408,8339,'','".AddSlashes(pg_result($resaco,$iresaco,'y06_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1408,8340,'','".AddSlashes(pg_result($resaco,$iresaco,'y06_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1408,8341,'','".AddSlashes(pg_result($resaco,$iresaco,'y06_codtipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1408,10663,'','".AddSlashes(pg_result($resaco,$iresaco,'y06_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from vistoriaslote
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($y06_vistoriaslote != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " y06_vistoriaslote = $y06_vistoriaslote ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Lote das vistorias nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$y06_vistoriaslote;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Lote das vistorias nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$y06_vistoriaslote;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$y06_vistoriaslote;
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
        $this->erro_sql   = "Record Vazio na Tabela:vistoriaslote";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $y06_vistoriaslote=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from vistoriaslote ";
     $sql .= "      inner join db_config  on  db_config.codigo = vistoriaslote.y06_instit";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = vistoriaslote.y06_usuario";
     $sql .= "      inner join tipovistorias  on  tipovistorias.y77_codtipo = vistoriaslote.y06_codtipo";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = db_config.numcgm";
     $sql .= "      inner join db_config  on  db_config.codigo = tipovistorias.y77_instit";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = tipovistorias.y77_coddepto";
     $sql .= "      inner join tipoandam  on  tipoandam.y41_codtipo = tipovistorias.y77_tipoandam";
     $sql2 = "";
     if($dbwhere==""){
       if($y06_vistoriaslote!=null ){
         $sql2 .= " where vistoriaslote.y06_vistoriaslote = $y06_vistoriaslote "; 
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
   function sql_query_file ( $y06_vistoriaslote=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from vistoriaslote ";
     $sql2 = "";
     if($dbwhere==""){
       if($y06_vistoriaslote!=null ){
         $sql2 .= " where vistoriaslote.y06_vistoriaslote = $y06_vistoriaslote "; 
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