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

//MODULO: agua
//CLASSE DA ENTIDADE aguacortematmov
class cl_aguacortematmov { 
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
   var $x42_codmov = 0; 
   var $x42_codsituacao = 0; 
   var $x42_codcortemat = 0; 
   var $x42_historico = null; 
   var $x42_data_dia = null; 
   var $x42_data_mes = null; 
   var $x42_data_ano = null; 
   var $x42_data = null; 
   var $x42_usuario = 0; 
   var $x42_leitura = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 x42_codmov = int4 = Código 
                 x42_codsituacao = int4 = Situação 
                 x42_codcortemat = int4 = Corte Matricula 
                 x42_historico = text = Histórico 
                 x42_data = date = Data 
                 x42_usuario = int4 = Usuário 
                 x42_leitura = int8 = Última Leitura do Hidrômetro 
                 ";
   //funcao construtor da classe 
   function cl_aguacortematmov() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("aguacortematmov"); 
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
       $this->x42_codmov = ($this->x42_codmov == ""?@$GLOBALS["HTTP_POST_VARS"]["x42_codmov"]:$this->x42_codmov);
       $this->x42_codsituacao = ($this->x42_codsituacao == ""?@$GLOBALS["HTTP_POST_VARS"]["x42_codsituacao"]:$this->x42_codsituacao);
       $this->x42_codcortemat = ($this->x42_codcortemat == ""?@$GLOBALS["HTTP_POST_VARS"]["x42_codcortemat"]:$this->x42_codcortemat);
       $this->x42_historico = ($this->x42_historico == ""?@$GLOBALS["HTTP_POST_VARS"]["x42_historico"]:$this->x42_historico);
       if($this->x42_data == ""){
         $this->x42_data_dia = ($this->x42_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["x42_data_dia"]:$this->x42_data_dia);
         $this->x42_data_mes = ($this->x42_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["x42_data_mes"]:$this->x42_data_mes);
         $this->x42_data_ano = ($this->x42_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["x42_data_ano"]:$this->x42_data_ano);
         if($this->x42_data_dia != ""){
            $this->x42_data = $this->x42_data_ano."-".$this->x42_data_mes."-".$this->x42_data_dia;
         }
       }
       $this->x42_usuario = ($this->x42_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["x42_usuario"]:$this->x42_usuario);
       $this->x42_leitura = ($this->x42_leitura == ""?@$GLOBALS["HTTP_POST_VARS"]["x42_leitura"]:$this->x42_leitura);
     }else{
       $this->x42_codmov = ($this->x42_codmov == ""?@$GLOBALS["HTTP_POST_VARS"]["x42_codmov"]:$this->x42_codmov);
     }
   }
   // funcao para inclusao
   function incluir ($x42_codmov){ 
      $this->atualizacampos();
     if($this->x42_codsituacao == null ){ 
       $this->erro_sql = " Campo Situação nao Informado.";
       $this->erro_campo = "x42_codsituacao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->x42_codcortemat == null ){ 
       $this->erro_sql = " Campo Corte Matricula nao Informado.";
       $this->erro_campo = "x42_codcortemat";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->x42_historico == null ){ 
       $this->erro_sql = " Campo Histórico nao Informado.";
       $this->erro_campo = "x42_historico";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->x42_data == null ){ 
       $this->erro_sql = " Campo Data nao Informado.";
       $this->erro_campo = "x42_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->x42_usuario == null ){ 
       $this->erro_sql = " Campo Usuário nao Informado.";
       $this->erro_campo = "x42_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->x42_leitura == null ){ 
       $this->x42_leitura = "0";
     }
     if($x42_codmov == "" || $x42_codmov == null ){
       $result = db_query("select nextval('aguacortematmov_x42_codmov_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: aguacortematmov_x42_codmov_seq do campo: x42_codmov"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->x42_codmov = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from aguacortematmov_x42_codmov_seq");
       if(($result != false) && (pg_result($result,0,0) < $x42_codmov)){
         $this->erro_sql = " Campo x42_codmov maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->x42_codmov = $x42_codmov; 
       }
     }
     if(($this->x42_codmov == null) || ($this->x42_codmov == "") ){ 
       $this->erro_sql = " Campo x42_codmov nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into aguacortematmov(
                                       x42_codmov 
                                      ,x42_codsituacao 
                                      ,x42_codcortemat 
                                      ,x42_historico 
                                      ,x42_data 
                                      ,x42_usuario 
                                      ,x42_leitura 
                       )
                values (
                                $this->x42_codmov 
                               ,$this->x42_codsituacao 
                               ,$this->x42_codcortemat 
                               ,'$this->x42_historico' 
                               ,".($this->x42_data == "null" || $this->x42_data == ""?"null":"'".$this->x42_data."'")." 
                               ,$this->x42_usuario 
                               ,$this->x42_leitura 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "aguacortematmov ($this->x42_codmov) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "aguacortematmov já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "aguacortematmov ($this->x42_codmov) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->x42_codmov;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->x42_codmov));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,8553,'$this->x42_codmov','I')");
       $resac = db_query("insert into db_acount values($acount,1456,8553,'','".AddSlashes(pg_result($resaco,0,'x42_codmov'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1456,8556,'','".AddSlashes(pg_result($resaco,0,'x42_codsituacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1456,8557,'','".AddSlashes(pg_result($resaco,0,'x42_codcortemat'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1456,8558,'','".AddSlashes(pg_result($resaco,0,'x42_historico'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1456,8559,'','".AddSlashes(pg_result($resaco,0,'x42_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1456,8560,'','".AddSlashes(pg_result($resaco,0,'x42_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1456,17613,'','".AddSlashes(pg_result($resaco,0,'x42_leitura'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($x42_codmov=null) { 
      $this->atualizacampos();
     $sql = " update aguacortematmov set ";
     $virgula = "";
     if(trim($this->x42_codmov)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x42_codmov"])){ 
       $sql  .= $virgula." x42_codmov = $this->x42_codmov ";
       $virgula = ",";
       if(trim($this->x42_codmov) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "x42_codmov";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->x42_codsituacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x42_codsituacao"])){ 
       $sql  .= $virgula." x42_codsituacao = $this->x42_codsituacao ";
       $virgula = ",";
       if(trim($this->x42_codsituacao) == null ){ 
         $this->erro_sql = " Campo Situação nao Informado.";
         $this->erro_campo = "x42_codsituacao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->x42_codcortemat)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x42_codcortemat"])){ 
       $sql  .= $virgula." x42_codcortemat = $this->x42_codcortemat ";
       $virgula = ",";
       if(trim($this->x42_codcortemat) == null ){ 
         $this->erro_sql = " Campo Corte Matricula nao Informado.";
         $this->erro_campo = "x42_codcortemat";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->x42_historico)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x42_historico"])){ 
       $sql  .= $virgula." x42_historico = '$this->x42_historico' ";
       $virgula = ",";
       if(trim($this->x42_historico) == null ){ 
         $this->erro_sql = " Campo Histórico nao Informado.";
         $this->erro_campo = "x42_historico";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->x42_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x42_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["x42_data_dia"] !="") ){ 
       $sql  .= $virgula." x42_data = '$this->x42_data' ";
       $virgula = ",";
       if(trim($this->x42_data) == null ){ 
         $this->erro_sql = " Campo Data nao Informado.";
         $this->erro_campo = "x42_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["x42_data_dia"])){ 
         $sql  .= $virgula." x42_data = null ";
         $virgula = ",";
         if(trim($this->x42_data) == null ){ 
           $this->erro_sql = " Campo Data nao Informado.";
           $this->erro_campo = "x42_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->x42_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x42_usuario"])){ 
       $sql  .= $virgula." x42_usuario = $this->x42_usuario ";
       $virgula = ",";
       if(trim($this->x42_usuario) == null ){ 
         $this->erro_sql = " Campo Usuário nao Informado.";
         $this->erro_campo = "x42_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->x42_leitura)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x42_leitura"])){ 
        if(trim($this->x42_leitura)=="" && isset($GLOBALS["HTTP_POST_VARS"]["x42_leitura"])){ 
           $this->x42_leitura = "0" ; 
        } 
       $sql  .= $virgula." x42_leitura = $this->x42_leitura ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($x42_codmov!=null){
       $sql .= " x42_codmov = $this->x42_codmov";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->x42_codmov));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,8553,'$this->x42_codmov','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x42_codmov"]) || $this->x42_codmov != "")
           $resac = db_query("insert into db_acount values($acount,1456,8553,'".AddSlashes(pg_result($resaco,$conresaco,'x42_codmov'))."','$this->x42_codmov',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x42_codsituacao"]) || $this->x42_codsituacao != "")
           $resac = db_query("insert into db_acount values($acount,1456,8556,'".AddSlashes(pg_result($resaco,$conresaco,'x42_codsituacao'))."','$this->x42_codsituacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x42_codcortemat"]) || $this->x42_codcortemat != "")
           $resac = db_query("insert into db_acount values($acount,1456,8557,'".AddSlashes(pg_result($resaco,$conresaco,'x42_codcortemat'))."','$this->x42_codcortemat',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x42_historico"]) || $this->x42_historico != "")
           $resac = db_query("insert into db_acount values($acount,1456,8558,'".AddSlashes(pg_result($resaco,$conresaco,'x42_historico'))."','$this->x42_historico',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x42_data"]) || $this->x42_data != "")
           $resac = db_query("insert into db_acount values($acount,1456,8559,'".AddSlashes(pg_result($resaco,$conresaco,'x42_data'))."','$this->x42_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x42_usuario"]) || $this->x42_usuario != "")
           $resac = db_query("insert into db_acount values($acount,1456,8560,'".AddSlashes(pg_result($resaco,$conresaco,'x42_usuario'))."','$this->x42_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x42_leitura"]) || $this->x42_leitura != "")
           $resac = db_query("insert into db_acount values($acount,1456,17613,'".AddSlashes(pg_result($resaco,$conresaco,'x42_leitura'))."','$this->x42_leitura',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "aguacortematmov nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->x42_codmov;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "aguacortematmov nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->x42_codmov;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->x42_codmov;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($x42_codmov=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($x42_codmov));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,8553,'$x42_codmov','E')");
         $resac = db_query("insert into db_acount values($acount,1456,8553,'','".AddSlashes(pg_result($resaco,$iresaco,'x42_codmov'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1456,8556,'','".AddSlashes(pg_result($resaco,$iresaco,'x42_codsituacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1456,8557,'','".AddSlashes(pg_result($resaco,$iresaco,'x42_codcortemat'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1456,8558,'','".AddSlashes(pg_result($resaco,$iresaco,'x42_historico'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1456,8559,'','".AddSlashes(pg_result($resaco,$iresaco,'x42_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1456,8560,'','".AddSlashes(pg_result($resaco,$iresaco,'x42_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1456,17613,'','".AddSlashes(pg_result($resaco,$iresaco,'x42_leitura'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from aguacortematmov
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($x42_codmov != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " x42_codmov = $x42_codmov ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "aguacortematmov nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$x42_codmov;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "aguacortematmov nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$x42_codmov;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$x42_codmov;
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
        $this->erro_sql   = "Record Vazio na Tabela:aguacortematmov";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $x42_codmov=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from aguacortematmov ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = aguacortematmov.x42_usuario";
     $sql .= "      inner join aguacortemat  on  aguacortemat.x41_codcortemat = aguacortematmov.x42_codcortemat";
     $sql .= "      inner join aguacortesituacao  on  aguacortesituacao.x43_codsituacao = aguacortematmov.x42_codsituacao";
     $sql .= "      inner join aguabase  on  aguabase.x01_matric = aguacortemat.x41_matric";
     $sql .= "      inner join aguacorte  as a on   a.x40_codcorte = aguacortemat.x41_codcorte";
     $sql2 = "";
     if($dbwhere==""){
       if($x42_codmov!=null ){
         $sql2 .= " where aguacortematmov.x42_codmov = $x42_codmov "; 
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
   function sql_query_file ( $x42_codmov=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from aguacortematmov ";
     $sql2 = "";
     if($dbwhere==""){
       if($x42_codmov!=null ){
         $sql2 .= " where aguacortematmov.x42_codmov = $x42_codmov "; 
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