<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica             
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

//MODULO: prefeitura
//CLASSE DA ENTIDADE db_daevalores
class cl_db_daevalores { 
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
   var $w07_codigo = 0; 
   var $w07_item = 0; 
   var $w07_mes = null; 
   var $w07_valor = 0; 
   var $w07_aliquota = 0; 
   var $w07_imposto = 0; 
   var $w07_dtpaga_dia = null; 
   var $w07_dtpaga_mes = null; 
   var $w07_dtpaga_ano = null; 
   var $w07_dtpaga = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 w07_codigo = int4 = Código dae 
                 w07_item = int4 = código do item 
                 w07_mes = varchar(2) = mês 
                 w07_valor = float8 = Valor da receita 
                 w07_aliquota = int4 = Aliquota 
                 w07_imposto = int4 = Imposto 
                 w07_dtpaga = date = Data de pagamento 
                 ";
   //funcao construtor da classe 
   function cl_db_daevalores() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("db_daevalores"); 
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
       $this->w07_codigo = ($this->w07_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["w07_codigo"]:$this->w07_codigo);
       $this->w07_item = ($this->w07_item == ""?@$GLOBALS["HTTP_POST_VARS"]["w07_item"]:$this->w07_item);
       $this->w07_mes = ($this->w07_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["w07_mes"]:$this->w07_mes);
       $this->w07_valor = ($this->w07_valor == ""?@$GLOBALS["HTTP_POST_VARS"]["w07_valor"]:$this->w07_valor);
       $this->w07_aliquota = ($this->w07_aliquota == ""?@$GLOBALS["HTTP_POST_VARS"]["w07_aliquota"]:$this->w07_aliquota);
       $this->w07_imposto = ($this->w07_imposto == ""?@$GLOBALS["HTTP_POST_VARS"]["w07_imposto"]:$this->w07_imposto);
       if($this->w07_dtpaga == ""){
         $this->w07_dtpaga_dia = ($this->w07_dtpaga_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["w07_dtpaga_dia"]:$this->w07_dtpaga_dia);
         $this->w07_dtpaga_mes = ($this->w07_dtpaga_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["w07_dtpaga_mes"]:$this->w07_dtpaga_mes);
         $this->w07_dtpaga_ano = ($this->w07_dtpaga_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["w07_dtpaga_ano"]:$this->w07_dtpaga_ano);
         if($this->w07_dtpaga_dia != ""){
            $this->w07_dtpaga = $this->w07_dtpaga_ano."-".$this->w07_dtpaga_mes."-".$this->w07_dtpaga_dia;
         }
       }
     }else{
       $this->w07_codigo = ($this->w07_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["w07_codigo"]:$this->w07_codigo);
       $this->w07_item = ($this->w07_item == ""?@$GLOBALS["HTTP_POST_VARS"]["w07_item"]:$this->w07_item);
     }
   }
   // funcao para inclusao
   function incluir ($w07_codigo,$w07_item){ 
      $this->atualizacampos();
     if($this->w07_mes == null ){ 
       $this->erro_sql = " Campo mês nao Informado.";
       $this->erro_campo = "w07_mes";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->w07_valor == null ){ 
       $this->erro_sql = " Campo Valor da receita nao Informado.";
       $this->erro_campo = "w07_valor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->w07_aliquota == null ){ 
       $this->erro_sql = " Campo Aliquota nao Informado.";
       $this->erro_campo = "w07_aliquota";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->w07_imposto == null ){ 
       $this->erro_sql = " Campo Imposto nao Informado.";
       $this->erro_campo = "w07_imposto";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->w07_dtpaga == null ){ 
       $this->erro_sql = " Campo Data de pagamento nao Informado.";
       $this->erro_campo = "w07_dtpaga_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->w07_codigo = $w07_codigo; 
       $this->w07_item = $w07_item; 
     if(($this->w07_codigo == null) || ($this->w07_codigo == "") ){ 
       $this->erro_sql = " Campo w07_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->w07_item == null) || ($this->w07_item == "") ){ 
       $this->erro_sql = " Campo w07_item nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into db_daevalores(
                                       w07_codigo 
                                      ,w07_item 
                                      ,w07_mes 
                                      ,w07_valor 
                                      ,w07_aliquota 
                                      ,w07_imposto 
                                      ,w07_dtpaga 
                       )
                values (
                                $this->w07_codigo 
                               ,$this->w07_item 
                               ,'$this->w07_mes' 
                               ,$this->w07_valor 
                               ,$this->w07_aliquota 
                               ,$this->w07_imposto 
                               ,".($this->w07_dtpaga == "null" || $this->w07_dtpaga == ""?"null":"'".$this->w07_dtpaga."'")." 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "tabela de valores do dae ($this->w07_codigo."-".$this->w07_item) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "tabela de valores do dae já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "tabela de valores do dae ($this->w07_codigo."-".$this->w07_item) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->w07_codigo."-".$this->w07_item;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->w07_codigo,$this->w07_item));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,4649,'$this->w07_codigo','I')");
       $resac = db_query("insert into db_acountkey values($acount,4650,'$this->w07_item','I')");
       $resac = db_query("insert into db_acount values($acount,610,4649,'','".AddSlashes(pg_result($resaco,0,'w07_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,610,4650,'','".AddSlashes(pg_result($resaco,0,'w07_item'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,610,4651,'','".AddSlashes(pg_result($resaco,0,'w07_mes'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,610,4652,'','".AddSlashes(pg_result($resaco,0,'w07_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,610,4653,'','".AddSlashes(pg_result($resaco,0,'w07_aliquota'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,610,4654,'','".AddSlashes(pg_result($resaco,0,'w07_imposto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,610,4725,'','".AddSlashes(pg_result($resaco,0,'w07_dtpaga'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($w07_codigo=null,$w07_item=null) { 
      $this->atualizacampos();
     $sql = " update db_daevalores set ";
     $virgula = "";
     if(trim($this->w07_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["w07_codigo"])){ 
       $sql  .= $virgula." w07_codigo = $this->w07_codigo ";
       $virgula = ",";
       if(trim($this->w07_codigo) == null ){ 
         $this->erro_sql = " Campo Código dae nao Informado.";
         $this->erro_campo = "w07_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->w07_item)!="" || isset($GLOBALS["HTTP_POST_VARS"]["w07_item"])){ 
       $sql  .= $virgula." w07_item = $this->w07_item ";
       $virgula = ",";
       if(trim($this->w07_item) == null ){ 
         $this->erro_sql = " Campo código do item nao Informado.";
         $this->erro_campo = "w07_item";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->w07_mes)!="" || isset($GLOBALS["HTTP_POST_VARS"]["w07_mes"])){ 
       $sql  .= $virgula." w07_mes = '$this->w07_mes' ";
       $virgula = ",";
       if(trim($this->w07_mes) == null ){ 
         $this->erro_sql = " Campo mês nao Informado.";
         $this->erro_campo = "w07_mes";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->w07_valor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["w07_valor"])){ 
       $sql  .= $virgula." w07_valor = $this->w07_valor ";
       $virgula = ",";
       if(trim($this->w07_valor) == null ){ 
         $this->erro_sql = " Campo Valor da receita nao Informado.";
         $this->erro_campo = "w07_valor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->w07_aliquota)!="" || isset($GLOBALS["HTTP_POST_VARS"]["w07_aliquota"])){ 
       $sql  .= $virgula." w07_aliquota = $this->w07_aliquota ";
       $virgula = ",";
       if(trim($this->w07_aliquota) == null ){ 
         $this->erro_sql = " Campo Aliquota nao Informado.";
         $this->erro_campo = "w07_aliquota";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->w07_imposto)!="" || isset($GLOBALS["HTTP_POST_VARS"]["w07_imposto"])){ 
       $sql  .= $virgula." w07_imposto = $this->w07_imposto ";
       $virgula = ",";
       if(trim($this->w07_imposto) == null ){ 
         $this->erro_sql = " Campo Imposto nao Informado.";
         $this->erro_campo = "w07_imposto";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->w07_dtpaga)!="" || isset($GLOBALS["HTTP_POST_VARS"]["w07_dtpaga_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["w07_dtpaga_dia"] !="") ){ 
       $sql  .= $virgula." w07_dtpaga = '$this->w07_dtpaga' ";
       $virgula = ",";
       if(trim($this->w07_dtpaga) == null ){ 
         $this->erro_sql = " Campo Data de pagamento nao Informado.";
         $this->erro_campo = "w07_dtpaga_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["w07_dtpaga_dia"])){ 
         $sql  .= $virgula." w07_dtpaga = null ";
         $virgula = ",";
         if(trim($this->w07_dtpaga) == null ){ 
           $this->erro_sql = " Campo Data de pagamento nao Informado.";
           $this->erro_campo = "w07_dtpaga_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     $sql .= " where ";
     if($w07_codigo!=null){
       $sql .= " w07_codigo = $this->w07_codigo";
     }
     if($w07_item!=null){
       $sql .= " and  w07_item = $this->w07_item";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->w07_codigo,$this->w07_item));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,4649,'$this->w07_codigo','A')");
         $resac = db_query("insert into db_acountkey values($acount,4650,'$this->w07_item','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["w07_codigo"]))
           $resac = db_query("insert into db_acount values($acount,610,4649,'".AddSlashes(pg_result($resaco,$conresaco,'w07_codigo'))."','$this->w07_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["w07_item"]))
           $resac = db_query("insert into db_acount values($acount,610,4650,'".AddSlashes(pg_result($resaco,$conresaco,'w07_item'))."','$this->w07_item',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["w07_mes"]))
           $resac = db_query("insert into db_acount values($acount,610,4651,'".AddSlashes(pg_result($resaco,$conresaco,'w07_mes'))."','$this->w07_mes',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["w07_valor"]))
           $resac = db_query("insert into db_acount values($acount,610,4652,'".AddSlashes(pg_result($resaco,$conresaco,'w07_valor'))."','$this->w07_valor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["w07_aliquota"]))
           $resac = db_query("insert into db_acount values($acount,610,4653,'".AddSlashes(pg_result($resaco,$conresaco,'w07_aliquota'))."','$this->w07_aliquota',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["w07_imposto"]))
           $resac = db_query("insert into db_acount values($acount,610,4654,'".AddSlashes(pg_result($resaco,$conresaco,'w07_imposto'))."','$this->w07_imposto',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["w07_dtpaga"]))
           $resac = db_query("insert into db_acount values($acount,610,4725,'".AddSlashes(pg_result($resaco,$conresaco,'w07_dtpaga'))."','$this->w07_dtpaga',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "tabela de valores do dae nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->w07_codigo."-".$this->w07_item;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "tabela de valores do dae nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->w07_codigo."-".$this->w07_item;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->w07_codigo."-".$this->w07_item;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($w07_codigo=null,$w07_item=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($w07_codigo,$w07_item));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,4649,'$w07_codigo','E')");
         $resac = db_query("insert into db_acountkey values($acount,4650,'$w07_item','E')");
         $resac = db_query("insert into db_acount values($acount,610,4649,'','".AddSlashes(pg_result($resaco,$iresaco,'w07_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,610,4650,'','".AddSlashes(pg_result($resaco,$iresaco,'w07_item'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,610,4651,'','".AddSlashes(pg_result($resaco,$iresaco,'w07_mes'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,610,4652,'','".AddSlashes(pg_result($resaco,$iresaco,'w07_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,610,4653,'','".AddSlashes(pg_result($resaco,$iresaco,'w07_aliquota'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,610,4654,'','".AddSlashes(pg_result($resaco,$iresaco,'w07_imposto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,610,4725,'','".AddSlashes(pg_result($resaco,$iresaco,'w07_dtpaga'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from db_daevalores
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($w07_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " w07_codigo = $w07_codigo ";
        }
        if($w07_item != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " w07_item = $w07_item ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "tabela de valores do dae nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$w07_codigo."-".$w07_item;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "tabela de valores do dae nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$w07_codigo."-".$w07_item;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$w07_codigo."-".$w07_item;
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
        $this->erro_sql   = "Record Vazio na Tabela:db_daevalores";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $w07_codigo=null,$w07_item=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from db_daevalores ";
     $sql .= "      inner join db_dae  on  db_dae.w04_codigo = db_daevalores.w07_codigo";
     $sql2 = "";
     if($dbwhere==""){
       if($w07_codigo!=null ){
         $sql2 .= " where db_daevalores.w07_codigo = $w07_codigo "; 
       } 
       if($w07_item!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " db_daevalores.w07_item = $w07_item "; 
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
   function sql_query_file ( $w07_codigo=null,$w07_item=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from db_daevalores ";
     $sql2 = "";
     if($dbwhere==""){
       if($w07_codigo!=null ){
         $sql2 .= " where db_daevalores.w07_codigo = $w07_codigo "; 
       } 
       if($w07_item!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " db_daevalores.w07_item = $w07_item "; 
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
