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

//MODULO: cadastro
//CLASSE DA ENTIDADE iptubase
class cl_iptubase { 
   // cria variaveis de erro 
   var $rotulo     = null; 
   var $query_sql  = null; 
   var $numrows    = 0; 
   var $erro_status= null; 
   var $erro_sql   = null; 
   var $erro_banco = null;  
   var $erro_msg   = null;  
   var $erro_campo = null;  
   var $pagina_retorno = null; 
   // cria variaveis do arquivo 
   var $j01_matric = 0; 
   var $j01_numcgm = 0; 
   var $j01_idbql = 0; 
   var $j01_baixa_dia = null; 
   var $j01_baixa_mes = null; 
   var $j01_baixa_ano = null; 
   var $j01_baixa = null; 
   var $j01_codave = 0; 
   var $j01_fracao = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 j01_matric = int4 = Matrícula Imóvel 
                 j01_numcgm = int4 = Numcgm 
                 j01_idbql = int4 = Id Lote 
                 j01_baixa = date = Baixa 
                 j01_codave = int4 = Codigo da Averbacao 
                 j01_fracao = float8 = Fracao Ideal 
                 ";
   //funcao construtor da classe 
   function cl_iptubase() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("iptubase"); 
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
       $this->j01_matric = ($this->j01_matric == ""?@$GLOBALS["HTTP_POST_VARS"]["j01_matric"]:$this->j01_matric);
       $this->j01_numcgm = ($this->j01_numcgm == ""?@$GLOBALS["HTTP_POST_VARS"]["j01_numcgm"]:$this->j01_numcgm);
       $this->j01_idbql = ($this->j01_idbql == ""?@$GLOBALS["HTTP_POST_VARS"]["j01_idbql"]:$this->j01_idbql);
       if($this->j01_baixa == ""){
         $this->j01_baixa_dia = @$GLOBALS["HTTP_POST_VARS"]["j01_baixa_dia"];
         $this->j01_baixa_mes = @$GLOBALS["HTTP_POST_VARS"]["j01_baixa_mes"];
         $this->j01_baixa_ano = @$GLOBALS["HTTP_POST_VARS"]["j01_baixa_ano"];
         if($this->j01_baixa_dia != ""){
            $this->j01_baixa = $this->j01_baixa_ano."-".$this->j01_baixa_mes."-".$this->j01_baixa_dia;
         }
       }
       $this->j01_codave = ($this->j01_codave == ""?@$GLOBALS["HTTP_POST_VARS"]["j01_codave"]:$this->j01_codave);
       $this->j01_fracao = ($this->j01_fracao == ""?@$GLOBALS["HTTP_POST_VARS"]["j01_fracao"]:$this->j01_fracao);
     }else{
       $this->j01_matric = ($this->j01_matric == ""?@$GLOBALS["HTTP_POST_VARS"]["j01_matric"]:$this->j01_matric);
     }
   }
   // funcao para inclusao
   function incluir ($j01_matric){ 
      $this->atualizacampos();
     if($this->j01_numcgm == null ){ 
       $this->erro_sql = " Campo Numcgm nao Informado.";
       $this->erro_campo = "j01_numcgm";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j01_idbql == null ){ 
       $this->erro_sql = " Campo Id Lote nao Informado.";
       $this->erro_campo = "j01_idbql";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j01_baixa == null ){ 
       $this->j01_baixa = "null";
     }
     if($this->j01_codave == null ){ 
       $this->j01_codave = "0";
     }
     if($this->j01_fracao == null ){ 
       $this->j01_fracao = "100";
     }
     if($j01_matric == "" || $j01_matric == null ){
       $result = @pg_query("select nextval('iptubase_j01_matric_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: iptubase_j01_matric_seq do campo: j01_matric"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->j01_matric = pg_result($result,0,0); 
     }else{
       $result = @pg_query("select last_value from iptubase_j01_matric_seq");
       if(($result != false) && (pg_result($result,0,0) < $j01_matric)){
         $this->erro_sql = " Campo j01_matric maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->j01_matric = $j01_matric; 
       }
     }
     if(($this->j01_matric == null) || ($this->j01_matric == "") ){ 
       $this->erro_sql = " Campo j01_matric nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
    			 
     $result = @pg_query("insert into iptubase(
                                       j01_matric 
                                      ,j01_numcgm 
                                      ,j01_idbql 
                                      ,j01_baixa 
                                      ,j01_codave 
                                      ,j01_fracao 
                       )
                values (
                                $this->j01_matric 
                               ,$this->j01_numcgm 
                               ,$this->j01_idbql 
                               ,".($this->j01_baixa == "null" || $this->j01_baixa == ""?"null":"'".$this->j01_baixa."'")." 
                               ,$this->j01_codave 
                               ,$this->j01_fracao 
                      )");
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Proprietario do Lote ($this->j01_matric) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Proprietario do Lote já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Proprietario do Lote ($this->j01_matric) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao Efetivada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->j01_matric;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $resaco = $this->sql_record($this->sql_query_file($this->j01_matric));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = pg_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = pg_query("insert into db_acountkey values($acount,141,'$this->j01_matric','I')");
       $resac = pg_query("insert into db_acount values($acount,27,141,'','".pg_result($resaco,0,'j01_matric')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,27,142,'','".pg_result($resaco,0,'j01_numcgm')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,27,143,'','".pg_result($resaco,0,'j01_idbql')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,27,144,'','".pg_result($resaco,0,'j01_baixa')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,27,145,'','".pg_result($resaco,0,'j01_codave')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,27,368,'','".pg_result($resaco,0,'j01_fracao')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($j01_matric=null) { 
      $this->atualizacampos();
     $sql = " update iptubase set ";
     $virgula = "";
     if(trim($this->j01_matric)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j01_matric"])){ 
        if(trim($this->j01_matric)=="" && isset($GLOBALS["HTTP_POST_VARS"]["j01_matric"])){ 
           $this->j01_matric = "0" ; 
        } 
       $sql  .= $virgula." j01_matric = $this->j01_matric ";
       $virgula = ",";
       if(trim($this->j01_matric) == null ){ 
         $this->erro_sql = " Campo Matrícula Imóvel nao Informado.";
         $this->erro_campo = "j01_matric";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j01_numcgm)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j01_numcgm"])){ 
        if(trim($this->j01_numcgm)=="" && isset($GLOBALS["HTTP_POST_VARS"]["j01_numcgm"])){ 
           $this->j01_numcgm = "0" ; 
        } 
       $sql  .= $virgula." j01_numcgm = $this->j01_numcgm ";
       $virgula = ",";
       if(trim($this->j01_numcgm) == null ){ 
         $this->erro_sql = " Campo Numcgm nao Informado.";
         $this->erro_campo = "j01_numcgm";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j01_idbql)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j01_idbql"])){ 
        if(trim($this->j01_idbql)=="" && isset($GLOBALS["HTTP_POST_VARS"]["j01_idbql"])){ 
           $this->j01_idbql = "0" ; 
        } 
       $sql  .= $virgula." j01_idbql = $this->j01_idbql ";
       $virgula = ",";
       if(trim($this->j01_idbql) == null ){ 
         $this->erro_sql = " Campo Id Lote nao Informado.";
         $this->erro_campo = "j01_idbql";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j01_baixa)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j01_baixa_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["j01_baixa_dia"] !="") ){ 
       $sql  .= $virgula." j01_baixa = '$this->j01_baixa' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["j01_baixa_dia"])){ 
         $sql  .= $virgula." j01_baixa = null ";
         $virgula = ",";
       }
     }
     if(trim($this->j01_codave)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j01_codave"])){ 
        if(trim($this->j01_codave)=="" && isset($GLOBALS["HTTP_POST_VARS"]["j01_codave"])){ 
           $this->j01_codave = "0" ; 
        } 
       $sql  .= $virgula." j01_codave = $this->j01_codave ";
       $virgula = ",";
     }
     if(trim($this->j01_fracao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j01_fracao"])){ 
        if(trim($this->j01_fracao)=="" && isset($GLOBALS["HTTP_POST_VARS"]["j01_fracao"])){ 
           $this->j01_fracao = "0" ; 
        } 
       $sql  .= $virgula." j01_fracao = $this->j01_fracao ";
       $virgula = ",";
     }
     $sql .= " where  j01_matric = $this->j01_matric
";
     $resaco = $this->sql_record($this->sql_query_file($this->j01_matric));
     if($this->numrows>0){       $resac = pg_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = pg_query("insert into db_acountkey values($acount,141,'$this->j01_matric','A')");
       if(isset($GLOBALS["HTTP_POST_VARS"]["j01_matric"]))
         $resac = pg_query("insert into db_acount values($acount,27,141,'".pg_result($resaco,0,'j01_matric')."','$this->j01_matric',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       if(isset($GLOBALS["HTTP_POST_VARS"]["j01_numcgm"]))
         $resac = pg_query("insert into db_acount values($acount,27,142,'".pg_result($resaco,0,'j01_numcgm')."','$this->j01_numcgm',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       if(isset($GLOBALS["HTTP_POST_VARS"]["j01_idbql"]))
         $resac = pg_query("insert into db_acount values($acount,27,143,'".pg_result($resaco,0,'j01_idbql')."','$this->j01_idbql',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       if(isset($GLOBALS["HTTP_POST_VARS"]["j01_baixa"]))
         $resac = pg_query("insert into db_acount values($acount,27,144,'".pg_result($resaco,0,'j01_baixa')."','$this->j01_baixa',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       if(isset($GLOBALS["HTTP_POST_VARS"]["j01_codave"]))
         $resac = pg_query("insert into db_acount values($acount,27,145,'".pg_result($resaco,0,'j01_codave')."','$this->j01_codave',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       if(isset($GLOBALS["HTTP_POST_VARS"]["j01_fracao"]))
         $resac = pg_query("insert into db_acount values($acount,27,368,'".pg_result($resaco,0,'j01_fracao')."','$this->j01_fracao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     $result = @pg_exec($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Proprietario do Lote nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->j01_matric;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Proprietario do Lote nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->j01_matric;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração Efetivada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->j01_matric;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($j01_matric=null) { 
     $this->atualizacampos(true);
     $resaco = $this->sql_record($this->sql_query_file($this->j01_matric));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = pg_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = pg_query("insert into db_acountkey values($acount,141,'$this->j01_matric','E')");
       $resac = pg_query("insert into db_acount values($acount,27,141,'','".pg_result($resaco,0,'j01_matric')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,27,142,'','".pg_result($resaco,0,'j01_numcgm')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,27,143,'','".pg_result($resaco,0,'j01_idbql')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,27,144,'','".pg_result($resaco,0,'j01_baixa')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,27,145,'','".pg_result($resaco,0,'j01_codave')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,27,368,'','".pg_result($resaco,0,'j01_fracao')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     $sql = " delete from iptubase
                    where ";
     $sql2 = "";
      if($this->j01_matric != ""){
      if($sql2!=""){
        $sql2 .= " and ";
      }
      $sql2 .= " j01_matric = $this->j01_matric ";
}
     $result = @pg_exec($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Proprietario do Lote nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$this->j01_matric;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Proprietario do Lote nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$this->j01_matric;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão Efetivada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->j01_matric;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         return true;
       } 
     } 
   } 
   // funcao do recordset 
   function sql_record($sql) { 
     $result = @pg_query($sql);
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
        $this->erro_sql   = "Dados do Grupo nao Encontrado";
        $this->erro_msg   = "Usuário: \n\n ".$this->erro_sql." \n\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $j01_matric=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from iptubase ";
     $sql .= "      inner join lote  on  lote.j34_idbql = iptubase.j01_idbql";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = iptubase.j01_numcgm";
     $sql .= "      inner join bairro  on  bairro.j13_codi = lote.j34_bairro";
     $sql .= "      inner join setor  on  setor.j30_codi = lote.j34_setor";
     $sql .= "      left outer join iptuant on iptubase.j01_matric = iptuant.j40_matric";
     $sql2 = "";
     if($dbwhere==""){
       if($j01_matric!=null ){
         $sql2 .= " where iptubase.j01_matric = $j01_matric "; 
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
   function sql_query_file ( $j01_matric=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from iptubase ";
     $sql2 = "";
     if($dbwhere==""){
       if($j01_matric!=null ){
         $sql2 .= " where iptubase.j01_matric = $j01_matric "; 
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
   function proprietario_query ( $j01_matric=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from proprietario";
     $sql2 = "";
     if($dbwhere==""){
       if($j01_matric!=null ){
         $sql2 .= " where proprietario.j01_matric = $j01_matric "; 
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
   function sqlmatriculas_setor($pesquisasetor=0){
    $sql = "select lote.j34_idbql as db_lote, j34_setor, j34_quadra, j34_lote
	              , j34_area, j34_areal,j01_matric,
	              ruas.j14_nome, bairro.j13_descr
                  from lote
				  inner join bairro on j13_codi = j34_bairro
				  inner join iptubase on j01_idbql = j34_idbql
                  left outer join testpri on j34_idbql = j49_idbql
				  left outer join ruas on j14_codigo = j49_codigo";
    if($pesquisasetor!=0){
       $sql .= " where j34_setor = $pesquisasetor";
     }
    return $sql;
}
   function sqlmatriculas_setorQuadra($pesquisasetor="",$pesquisaquadra=""){
    $sql = "select lote.j34_idbql as db_lote, j34_setor, j34_quadra, j34_lote
	              , j34_area, j34_areal,j01_matric,
	              ruas.j14_nome, bairro.j13_descr
                  from lote
				  inner join bairro on j13_codi = j34_bairro
				  inner join iptubase on j01_idbql = j34_idbql
				  left outer join testpri on j34_idbql = j49_idbql
				  left outer join ruas on j14_codigo = j49_codigo";
	 if($pesquisasetor != ""){
       $sql .= " where j34_setor = '".strtoupper($pesquisasetor)."' and j34_quadra = '".strtoupper($pesquisaquadra)."'";
     }
	 return $sql;
   }
   function sqlmatriculas_IDBQL($pesquisaPorIDBQL=0){
   $sql = "
    select distinct * from (  select j01_matric, 'PROPRIETARIO'::varchar(12) as proprietario, j01_idbql, cgm.z01_nome
                                      from iptubase
                                      inner join cgm on j01_numcgm = z01_numcgm
                                      where j01_idbql = $pesquisaPorIDBQL
     ) as dados
     inner join lote on j34_idbql = j01_idbql
     left outer join testpri on j49_idbql = j01_idbql
     left outer join ruas on j49_codigo = j14_codigo
     left outer join bairro on j34_bairro = j13_codi
   ";
   return $sql;
}
   function sqlmatriculas_nome($pesquisaPorNome=0){
$sql = "
select distinct * from (  select j01_matric, 'PROPRIETARIO'::varchar(12) as proprietario, j01_idbql, cgm.z01_nome
                                     from iptubase
                                     inner join cgm on j01_numcgm = z01_numcgm
                                     where j01_numcgm = $pesquisaPorNome
   union
                                      select j01_matric, 'OUTRO PROPR'::varchar(12) as proprietario, j01_idbql, cgm.z01_nome
                                      from propri
                                      inner join iptubase on j42_matric = j01_matric
                                      inner join cgm on j42_numcgm = z01_numcgm
                                      where j42_numcgm = $pesquisaPorNome
                                      union
                                      select j01_matric, 'PROMITENTE'::varchar(12) as proprietario, j01_idbql, cgm.z01_nome
                                      from promitente
                                      inner join iptubase on j41_matric = j01_matric
                                      inner join cgm on j41_numcgm = z01_numcgm
                                      where j41_numcgm = $pesquisaPorNome
	) as dados
	  inner join lote on j34_idbql = j01_idbql
	  left outer join testpri on j49_idbql = j01_idbql
          left outer join ruas on j49_codigo = j14_codigo
	  left outer join bairro on j34_bairro = j13_codi
	  left outer join iptuconstr on j39_matric = j01_matric
    ";
     //alterado 23/09/05 adcionada na altima linha do sql: 
     //left outer join iptuconstr on j39_matric = j01_matric
     //para pegar o complemento da matricula
     
    return $sql;
   }
   function sqlmatriculas_imobiliaria($pesquisaPorImobiliaria=0){
   $sql = "
    select distinct * from (  select j01_matric, c.z01_nome as proprietario, j01_idbql, cgm.z01_nome
                                   from imobil
 	                 inner join iptubase on j44_matric = j01_matric
                                      inner join cgm on j01_numcgm = cgm.z01_numcgm
	                 inner join cgm c on j44_numcgm = c.z01_numcgm 		
                                      where j44_numcgm = $pesquisaPorImobiliaria
	) as dados
	  inner join lote on j34_idbql = j01_idbql
	  left outer join testpri on j49_idbql = j01_idbql
	  left outer join ruas on j49_codigo = j14_codigo
	  left outer join bairro on j34_bairro = j13_codi
    ";
    return $sql;
   }
   function sqlmatriculas_bairros($pesquisaBairro=0){
  $sql = "
  select iptubase.j01_matric, cgm.z01_nome,cgm.z01_ender,cgm.z01_munic,cgm.z01_cep,cgm.z01_uf ,lote.*
          from lote
          inner join iptubase on j34_idbql = j01_idbql
          inner join cgm on z01_numcgm = j01_numcgm
   ";
  if($pesquisaBairro!=0){
       $sql .= "where j34_bairro = $pesquisaBairro";
   }
   return $sql;
}
   function proprietario_record($sql) { 
     $result = @pg_query($sql);
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
        $this->erro_sql   = "Proprietarios nao Encontrados";
        $this->erro_msg   = "Usuário: \n\n ".$this->erro_sql." \n\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sqlmatriculas_ruas($pesquisaRua=0,$numero=0,$filtrotipo='todos'){
     $sql = " select j01_matric,j01_tipoimp,z01_nome,j34_setor,j34_quadra,j34_lote,j39_numero,j39_compl,proprietario
              from proprietario";
     if($pesquisaRua!=0){
       $sql .= " where j14_codigo = $pesquisaRua or codpri = $pesquisaRua";
       if($numero!=0){
          $sql .= "  and  j39_numero >= $numero";
       }
     }
	 if($filtrotipo!='todos'){
	    $sql .= " and j01_tipoimp = '".$filtrotipo."'";
	 }
     $sql .= " order by j39_numero ";
     return $sql;
   }
}
?>