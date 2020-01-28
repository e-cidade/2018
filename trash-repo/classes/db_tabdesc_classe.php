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

//MODULO: caixa
//CLASSE DA ENTIDADE tabdesc
class cl_tabdesc { 
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
   var $codsubrec = 0; 
   var $k07_codigo = 0; 
   var $k07_descr = null; 
   var $k07_valorf = 0; 
   var $k07_valorv = 0; 
   var $k07_quamin = 0; 
   var $k07_percde = 0; 
   var $k07_data_dia = null; 
   var $k07_data_mes = null; 
   var $k07_data_ano = null; 
   var $k07_data = null; 
   var $k07_codinf = null; 
   var $k07_dtval_dia = null; 
   var $k07_dtval_mes = null; 
   var $k07_dtval_ano = null; 
   var $k07_dtval = null; 
   var $k07_instit = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 codsubrec = int4 = Código das Subreceitas 
                 k07_codigo = int4 = Código da receita 
                 k07_descr = varchar(60) = Descrição da subreceita 
                 k07_valorf = float8 = Valor Fixo 
                 k07_valorv = float8 = Valor Variável 
                 k07_quamin = float8 = Quantidade Mínima 
                 k07_percde = float8 = Percentual de Desconto 
                 k07_data = date = Data de criação 
                 k07_codinf = varchar(5) = Inflator para correção dos valores 
                 k07_dtval = date = Data de Validade 
                 k07_instit = int4 = Cód. Instituição 
                 ";
   //funcao construtor da classe 
   function cl_tabdesc() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("tabdesc"); 
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
       $this->codsubrec = ($this->codsubrec == ""?@$GLOBALS["HTTP_POST_VARS"]["codsubrec"]:$this->codsubrec);
       $this->k07_codigo = ($this->k07_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["k07_codigo"]:$this->k07_codigo);
       $this->k07_descr = ($this->k07_descr == ""?@$GLOBALS["HTTP_POST_VARS"]["k07_descr"]:$this->k07_descr);
       $this->k07_valorf = ($this->k07_valorf == ""?@$GLOBALS["HTTP_POST_VARS"]["k07_valorf"]:$this->k07_valorf);
       $this->k07_valorv = ($this->k07_valorv == ""?@$GLOBALS["HTTP_POST_VARS"]["k07_valorv"]:$this->k07_valorv);
       $this->k07_quamin = ($this->k07_quamin == ""?@$GLOBALS["HTTP_POST_VARS"]["k07_quamin"]:$this->k07_quamin);
       $this->k07_percde = ($this->k07_percde == ""?@$GLOBALS["HTTP_POST_VARS"]["k07_percde"]:$this->k07_percde);
       if($this->k07_data == ""){
         $this->k07_data_dia = ($this->k07_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["k07_data_dia"]:$this->k07_data_dia);
         $this->k07_data_mes = ($this->k07_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["k07_data_mes"]:$this->k07_data_mes);
         $this->k07_data_ano = ($this->k07_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["k07_data_ano"]:$this->k07_data_ano);
         if($this->k07_data_dia != ""){
            $this->k07_data = $this->k07_data_ano."-".$this->k07_data_mes."-".$this->k07_data_dia;
         }
       }
       $this->k07_codinf = ($this->k07_codinf == ""?@$GLOBALS["HTTP_POST_VARS"]["k07_codinf"]:$this->k07_codinf);
       if($this->k07_dtval == ""){
         $this->k07_dtval_dia = ($this->k07_dtval_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["k07_dtval_dia"]:$this->k07_dtval_dia);
         $this->k07_dtval_mes = ($this->k07_dtval_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["k07_dtval_mes"]:$this->k07_dtval_mes);
         $this->k07_dtval_ano = ($this->k07_dtval_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["k07_dtval_ano"]:$this->k07_dtval_ano);
         if($this->k07_dtval_dia != ""){
            $this->k07_dtval = $this->k07_dtval_ano."-".$this->k07_dtval_mes."-".$this->k07_dtval_dia;
         }
       }
       $this->k07_instit = ($this->k07_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["k07_instit"]:$this->k07_instit);
     }else{
       $this->codsubrec = ($this->codsubrec == ""?@$GLOBALS["HTTP_POST_VARS"]["codsubrec"]:$this->codsubrec);
     }
   }
   // funcao para inclusao
   function incluir ($codsubrec){ 
      $this->atualizacampos();
     if($this->k07_codigo == null ){ 
       $this->erro_sql = " Campo Código da receita nao Informado.";
       $this->erro_campo = "k07_codigo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k07_descr == null ){ 
       $this->erro_sql = " Campo Descrição da subreceita nao Informado.";
       $this->erro_campo = "k07_descr";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k07_valorf == null ){ 
       $this->erro_sql = " Campo Valor Fixo nao Informado.";
       $this->erro_campo = "k07_valorf";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k07_valorv == null ){ 
       $this->erro_sql = " Campo Valor Variável nao Informado.";
       $this->erro_campo = "k07_valorv";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k07_quamin == null ){ 
       $this->erro_sql = " Campo Quantidade Mínima nao Informado.";
       $this->erro_campo = "k07_quamin";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k07_percde == null ){ 
       $this->erro_sql = " Campo Percentual de Desconto nao Informado.";
       $this->erro_campo = "k07_percde";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k07_data == null ){ 
       $this->erro_sql = " Campo Data de criação nao Informado.";
       $this->erro_campo = "k07_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k07_codinf == null ){ 
       $this->erro_sql = " Campo Inflator para correção dos valores nao Informado.";
       $this->erro_campo = "k07_codinf";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k07_dtval == null ){ 
       $this->k07_dtval = "null";
     }
     if($this->k07_instit == null ){ 
       $this->erro_sql = " Campo Cód. Instituição nao Informado.";
       $this->erro_campo = "k07_instit";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($codsubrec == "" || $codsubrec == null ){
       $result = db_query("select nextval('tabdesc_codsubrec_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: tabdesc_codsubrec_seq do campo: codsubrec"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->codsubrec = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from tabdesc_codsubrec_seq");
       if(($result != false) && (pg_result($result,0,0) < $codsubrec)){
         $this->erro_sql = " Campo codsubrec maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->codsubrec = $codsubrec; 
       }
     }
     if(($this->codsubrec == null) || ($this->codsubrec == "") ){ 
       $this->erro_sql = " Campo codsubrec nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into tabdesc(
                                       codsubrec 
                                      ,k07_codigo 
                                      ,k07_descr 
                                      ,k07_valorf 
                                      ,k07_valorv 
                                      ,k07_quamin 
                                      ,k07_percde 
                                      ,k07_data 
                                      ,k07_codinf 
                                      ,k07_dtval 
                                      ,k07_instit 
                       )
                values (
                                $this->codsubrec 
                               ,$this->k07_codigo 
                               ,'$this->k07_descr' 
                               ,$this->k07_valorf 
                               ,$this->k07_valorv 
                               ,$this->k07_quamin 
                               ,$this->k07_percde 
                               ,".($this->k07_data == "null" || $this->k07_data == ""?"null":"'".$this->k07_data."'")." 
                               ,'$this->k07_codinf' 
                               ,".($this->k07_dtval == "null" || $this->k07_dtval == ""?"null":"'".$this->k07_dtval."'")." 
                               ,$this->k07_instit 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = " ($this->codsubrec) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = " já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = " ($this->codsubrec) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->codsubrec;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->codsubrec));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,439,'$this->codsubrec','I')");
       $resac = db_query("insert into db_acount values($acount,79,439,'','".AddSlashes(pg_result($resaco,0,'codsubrec'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,79,431,'','".AddSlashes(pg_result($resaco,0,'k07_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,79,432,'','".AddSlashes(pg_result($resaco,0,'k07_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,79,433,'','".AddSlashes(pg_result($resaco,0,'k07_valorf'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,79,434,'','".AddSlashes(pg_result($resaco,0,'k07_valorv'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,79,435,'','".AddSlashes(pg_result($resaco,0,'k07_quamin'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,79,436,'','".AddSlashes(pg_result($resaco,0,'k07_percde'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,79,437,'','".AddSlashes(pg_result($resaco,0,'k07_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,79,438,'','".AddSlashes(pg_result($resaco,0,'k07_codinf'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,79,4666,'','".AddSlashes(pg_result($resaco,0,'k07_dtval'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,79,10668,'','".AddSlashes(pg_result($resaco,0,'k07_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($codsubrec=null) { 
      $this->atualizacampos();
     $sql = " update tabdesc set ";
     $virgula = "";
     if(trim($this->codsubrec)!="" || isset($GLOBALS["HTTP_POST_VARS"]["codsubrec"])){ 
       $sql  .= $virgula." codsubrec = $this->codsubrec ";
       $virgula = ",";
       if(trim($this->codsubrec) == null ){ 
         $this->erro_sql = " Campo Código das Subreceitas nao Informado.";
         $this->erro_campo = "codsubrec";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k07_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k07_codigo"])){ 
       $sql  .= $virgula." k07_codigo = $this->k07_codigo ";
       $virgula = ",";
       if(trim($this->k07_codigo) == null ){ 
         $this->erro_sql = " Campo Código da receita nao Informado.";
         $this->erro_campo = "k07_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k07_descr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k07_descr"])){ 
       $sql  .= $virgula." k07_descr = '$this->k07_descr' ";
       $virgula = ",";
       if(trim($this->k07_descr) == null ){ 
         $this->erro_sql = " Campo Descrição da subreceita nao Informado.";
         $this->erro_campo = "k07_descr";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k07_valorf)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k07_valorf"])){ 
       $sql  .= $virgula." k07_valorf = $this->k07_valorf ";
       $virgula = ",";
       if(trim($this->k07_valorf) == null ){ 
         $this->erro_sql = " Campo Valor Fixo nao Informado.";
         $this->erro_campo = "k07_valorf";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k07_valorv)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k07_valorv"])){ 
       $sql  .= $virgula." k07_valorv = $this->k07_valorv ";
       $virgula = ",";
       if(trim($this->k07_valorv) == null ){ 
         $this->erro_sql = " Campo Valor Variável nao Informado.";
         $this->erro_campo = "k07_valorv";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k07_quamin)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k07_quamin"])){ 
       $sql  .= $virgula." k07_quamin = $this->k07_quamin ";
       $virgula = ",";
       if(trim($this->k07_quamin) == null ){ 
         $this->erro_sql = " Campo Quantidade Mínima nao Informado.";
         $this->erro_campo = "k07_quamin";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k07_percde)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k07_percde"])){ 
       $sql  .= $virgula." k07_percde = $this->k07_percde ";
       $virgula = ",";
       if(trim($this->k07_percde) == null ){ 
         $this->erro_sql = " Campo Percentual de Desconto nao Informado.";
         $this->erro_campo = "k07_percde";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k07_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k07_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["k07_data_dia"] !="") ){ 
       $sql  .= $virgula." k07_data = '$this->k07_data' ";
       $virgula = ",";
       if(trim($this->k07_data) == null ){ 
         $this->erro_sql = " Campo Data de criação nao Informado.";
         $this->erro_campo = "k07_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["k07_data_dia"])){ 
         $sql  .= $virgula." k07_data = null ";
         $virgula = ",";
         if(trim($this->k07_data) == null ){ 
           $this->erro_sql = " Campo Data de criação nao Informado.";
           $this->erro_campo = "k07_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->k07_codinf)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k07_codinf"])){ 
       $sql  .= $virgula." k07_codinf = '$this->k07_codinf' ";
       $virgula = ",";
       if(trim($this->k07_codinf) == null ){ 
         $this->erro_sql = " Campo Inflator para correção dos valores nao Informado.";
         $this->erro_campo = "k07_codinf";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k07_dtval)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k07_dtval_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["k07_dtval_dia"] !="") ){ 
       $sql  .= $virgula." k07_dtval = '$this->k07_dtval' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["k07_dtval_dia"])){ 
         $sql  .= $virgula." k07_dtval = null ";
         $virgula = ",";
       }
     }
     if(trim($this->k07_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k07_instit"])){ 
       $sql  .= $virgula." k07_instit = $this->k07_instit ";
       $virgula = ",";
       if(trim($this->k07_instit) == null ){ 
         $this->erro_sql = " Campo Cód. Instituição nao Informado.";
         $this->erro_campo = "k07_instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($codsubrec!=null){
       $sql .= " codsubrec = $this->codsubrec";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->codsubrec));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,439,'$this->codsubrec','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["codsubrec"]))
           $resac = db_query("insert into db_acount values($acount,79,439,'".AddSlashes(pg_result($resaco,$conresaco,'codsubrec'))."','$this->codsubrec',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k07_codigo"]))
           $resac = db_query("insert into db_acount values($acount,79,431,'".AddSlashes(pg_result($resaco,$conresaco,'k07_codigo'))."','$this->k07_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k07_descr"]))
           $resac = db_query("insert into db_acount values($acount,79,432,'".AddSlashes(pg_result($resaco,$conresaco,'k07_descr'))."','$this->k07_descr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k07_valorf"]))
           $resac = db_query("insert into db_acount values($acount,79,433,'".AddSlashes(pg_result($resaco,$conresaco,'k07_valorf'))."','$this->k07_valorf',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k07_valorv"]))
           $resac = db_query("insert into db_acount values($acount,79,434,'".AddSlashes(pg_result($resaco,$conresaco,'k07_valorv'))."','$this->k07_valorv',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k07_quamin"]))
           $resac = db_query("insert into db_acount values($acount,79,435,'".AddSlashes(pg_result($resaco,$conresaco,'k07_quamin'))."','$this->k07_quamin',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k07_percde"]))
           $resac = db_query("insert into db_acount values($acount,79,436,'".AddSlashes(pg_result($resaco,$conresaco,'k07_percde'))."','$this->k07_percde',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k07_data"]))
           $resac = db_query("insert into db_acount values($acount,79,437,'".AddSlashes(pg_result($resaco,$conresaco,'k07_data'))."','$this->k07_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k07_codinf"]))
           $resac = db_query("insert into db_acount values($acount,79,438,'".AddSlashes(pg_result($resaco,$conresaco,'k07_codinf'))."','$this->k07_codinf',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k07_dtval"]))
           $resac = db_query("insert into db_acount values($acount,79,4666,'".AddSlashes(pg_result($resaco,$conresaco,'k07_dtval'))."','$this->k07_dtval',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k07_instit"]))
           $resac = db_query("insert into db_acount values($acount,79,10668,'".AddSlashes(pg_result($resaco,$conresaco,'k07_instit'))."','$this->k07_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = " nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->codsubrec;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = " nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->codsubrec;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->codsubrec;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($codsubrec=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($codsubrec));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,439,'$codsubrec','E')");
         $resac = db_query("insert into db_acount values($acount,79,439,'','".AddSlashes(pg_result($resaco,$iresaco,'codsubrec'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,79,431,'','".AddSlashes(pg_result($resaco,$iresaco,'k07_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,79,432,'','".AddSlashes(pg_result($resaco,$iresaco,'k07_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,79,433,'','".AddSlashes(pg_result($resaco,$iresaco,'k07_valorf'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,79,434,'','".AddSlashes(pg_result($resaco,$iresaco,'k07_valorv'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,79,435,'','".AddSlashes(pg_result($resaco,$iresaco,'k07_quamin'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,79,436,'','".AddSlashes(pg_result($resaco,$iresaco,'k07_percde'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,79,437,'','".AddSlashes(pg_result($resaco,$iresaco,'k07_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,79,438,'','".AddSlashes(pg_result($resaco,$iresaco,'k07_codinf'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,79,4666,'','".AddSlashes(pg_result($resaco,$iresaco,'k07_dtval'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,79,10668,'','".AddSlashes(pg_result($resaco,$iresaco,'k07_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from tabdesc
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($codsubrec != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " codsubrec = $codsubrec ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = " nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$codsubrec;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = " nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$codsubrec;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$codsubrec;
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
        $this->erro_sql   = "Record Vazio na Tabela:tabdesc";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $codsubrec=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from tabdesc ";
     $sql .= "      inner join tabrec  on  tabrec.k02_codigo = tabdesc.k07_codigo";
     $sql .= "      inner join inflan  on  inflan.i01_codigo = tabdesc.k07_codinf";
     $sql .= "      inner join db_config  on  db_config.codigo = tabdesc.k07_instit";
     $sql .= "      inner join tabrecjm  on  tabrecjm.k02_codjm = tabrec.k02_codjm";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = db_config.numcgm";
     $sql2 = "";
     if($dbwhere==""){
       if($codsubrec!=null ){
         $sql2 .= " where tabdesc.codsubrec = $codsubrec "; 
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
   function sql_query_file ( $codsubrec=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from tabdesc ";
     $sql2 = "";
     if($dbwhere==""){
       if($codsubrec!=null ){
         $sql2 .= " where tabdesc.codsubrec = $codsubrec "; 
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