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
//CLASSE DA ENTIDADE db_dairetido
class cl_db_dairetido { 
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
   var $w15_sequencial = 0; 
   var $w15_dai = 0; 
   var $w15_mes = 0; 
   var $w15_valreceita = 0; 
   var $w15_cnpj = null; 
   var $w15_nota = null; 
   var $w15_serie = null; 
   var $w15_data_dia = null; 
   var $w15_data_mes = null; 
   var $w15_data_ano = null; 
   var $w15_data = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 w15_sequencial = int4 = Sequencial 
                 w15_dai = int4 = Código do dae 
                 w15_mes = int4 = Mes 
                 w15_valreceita = float8 = valor da receita 
                 w15_cnpj = char(14) = CNPJ 
                 w15_nota = varchar(10) = Nota 
                 w15_serie = varchar(10) = Série 
                 w15_data = date = Data 
                 ";
   //funcao construtor da classe 
   function cl_db_dairetido() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("db_dairetido"); 
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
       $this->w15_sequencial = ($this->w15_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["w15_sequencial"]:$this->w15_sequencial);
       $this->w15_dai = ($this->w15_dai == ""?@$GLOBALS["HTTP_POST_VARS"]["w15_dai"]:$this->w15_dai);
       $this->w15_mes = ($this->w15_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["w15_mes"]:$this->w15_mes);
       $this->w15_valreceita = ($this->w15_valreceita == ""?@$GLOBALS["HTTP_POST_VARS"]["w15_valreceita"]:$this->w15_valreceita);
       $this->w15_cnpj = ($this->w15_cnpj == ""?@$GLOBALS["HTTP_POST_VARS"]["w15_cnpj"]:$this->w15_cnpj);
       $this->w15_nota = ($this->w15_nota == ""?@$GLOBALS["HTTP_POST_VARS"]["w15_nota"]:$this->w15_nota);
       $this->w15_serie = ($this->w15_serie == ""?@$GLOBALS["HTTP_POST_VARS"]["w15_serie"]:$this->w15_serie);
       if($this->w15_data == ""){
         $this->w15_data_dia = ($this->w15_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["w15_data_dia"]:$this->w15_data_dia);
         $this->w15_data_mes = ($this->w15_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["w15_data_mes"]:$this->w15_data_mes);
         $this->w15_data_ano = ($this->w15_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["w15_data_ano"]:$this->w15_data_ano);
         if($this->w15_data_dia != ""){
            $this->w15_data = $this->w15_data_ano."-".$this->w15_data_mes."-".$this->w15_data_dia;
         }
       }
     }else{
       $this->w15_sequencial = ($this->w15_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["w15_sequencial"]:$this->w15_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($w15_sequencial){ 
      $this->atualizacampos();
     if($this->w15_dai == null ){ 
       $this->erro_sql = " Campo Código do dae nao Informado.";
       $this->erro_campo = "w15_dai";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->w15_mes == null ){ 
       $this->erro_sql = " Campo Mes nao Informado.";
       $this->erro_campo = "w15_mes";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->w15_valreceita == null ){ 
       $this->erro_sql = " Campo valor da receita nao Informado.";
       $this->erro_campo = "w15_valreceita";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->w15_cnpj == null ){ 
       $this->erro_sql = " Campo CNPJ nao Informado.";
       $this->erro_campo = "w15_cnpj";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->w15_nota == null ){ 
       $this->erro_sql = " Campo Nota nao Informado.";
       $this->erro_campo = "w15_nota";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->w15_data == null ){ 
       $this->erro_sql = " Campo Data nao Informado.";
       $this->erro_campo = "w15_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($w15_sequencial == "" || $w15_sequencial == null ){
       $result = db_query("select nextval('db_dairetido_w15_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: db_dairetido_w15_sequencial_seq do campo: w15_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->w15_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from db_dairetido_w15_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $w15_sequencial)){
         $this->erro_sql = " Campo w15_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->w15_sequencial = $w15_sequencial; 
       }
     }
     if(($this->w15_sequencial == null) || ($this->w15_sequencial == "") ){ 
       $this->erro_sql = " Campo w15_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into db_dairetido(
                                       w15_sequencial 
                                      ,w15_dai 
                                      ,w15_mes 
                                      ,w15_valreceita 
                                      ,w15_cnpj 
                                      ,w15_nota 
                                      ,w15_serie 
                                      ,w15_data 
                       )
                values (
                                $this->w15_sequencial 
                               ,$this->w15_dai 
                               ,$this->w15_mes 
                               ,$this->w15_valreceita 
                               ,'$this->w15_cnpj' 
                               ,'$this->w15_nota' 
                               ,'$this->w15_serie' 
                               ,".($this->w15_data == "null" || $this->w15_data == ""?"null":"'".$this->w15_data."'")." 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Valores retidos de minha empresa ($this->w15_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Valores retidos de minha empresa já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Valores retidos de minha empresa ($this->w15_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->w15_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->w15_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,9131,'$this->w15_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,1563,9131,'','".AddSlashes(pg_result($resaco,0,'w15_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1563,9132,'','".AddSlashes(pg_result($resaco,0,'w15_dai'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1563,9133,'','".AddSlashes(pg_result($resaco,0,'w15_mes'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1563,9135,'','".AddSlashes(pg_result($resaco,0,'w15_valreceita'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1563,9137,'','".AddSlashes(pg_result($resaco,0,'w15_cnpj'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1563,9175,'','".AddSlashes(pg_result($resaco,0,'w15_nota'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1563,9176,'','".AddSlashes(pg_result($resaco,0,'w15_serie'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1563,9177,'','".AddSlashes(pg_result($resaco,0,'w15_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($w15_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update db_dairetido set ";
     $virgula = "";
     if(trim($this->w15_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["w15_sequencial"])){ 
       $sql  .= $virgula." w15_sequencial = $this->w15_sequencial ";
       $virgula = ",";
       if(trim($this->w15_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "w15_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->w15_dai)!="" || isset($GLOBALS["HTTP_POST_VARS"]["w15_dai"])){ 
       $sql  .= $virgula." w15_dai = $this->w15_dai ";
       $virgula = ",";
       if(trim($this->w15_dai) == null ){ 
         $this->erro_sql = " Campo Código do dae nao Informado.";
         $this->erro_campo = "w15_dai";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->w15_mes)!="" || isset($GLOBALS["HTTP_POST_VARS"]["w15_mes"])){ 
       $sql  .= $virgula." w15_mes = $this->w15_mes ";
       $virgula = ",";
       if(trim($this->w15_mes) == null ){ 
         $this->erro_sql = " Campo Mes nao Informado.";
         $this->erro_campo = "w15_mes";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->w15_valreceita)!="" || isset($GLOBALS["HTTP_POST_VARS"]["w15_valreceita"])){ 
       $sql  .= $virgula." w15_valreceita = $this->w15_valreceita ";
       $virgula = ",";
       if(trim($this->w15_valreceita) == null ){ 
         $this->erro_sql = " Campo valor da receita nao Informado.";
         $this->erro_campo = "w15_valreceita";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->w15_cnpj)!="" || isset($GLOBALS["HTTP_POST_VARS"]["w15_cnpj"])){ 
       $sql  .= $virgula." w15_cnpj = '$this->w15_cnpj' ";
       $virgula = ",";
       if(trim($this->w15_cnpj) == null ){ 
         $this->erro_sql = " Campo CNPJ nao Informado.";
         $this->erro_campo = "w15_cnpj";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->w15_nota)!="" || isset($GLOBALS["HTTP_POST_VARS"]["w15_nota"])){ 
       $sql  .= $virgula." w15_nota = '$this->w15_nota' ";
       $virgula = ",";
       if(trim($this->w15_nota) == null ){ 
         $this->erro_sql = " Campo Nota nao Informado.";
         $this->erro_campo = "w15_nota";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->w15_serie)!="" || isset($GLOBALS["HTTP_POST_VARS"]["w15_serie"])){ 
       $sql  .= $virgula." w15_serie = '$this->w15_serie' ";
       $virgula = ",";
     }
     if(trim($this->w15_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["w15_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["w15_data_dia"] !="") ){ 
       $sql  .= $virgula." w15_data = '$this->w15_data' ";
       $virgula = ",";
       if(trim($this->w15_data) == null ){ 
         $this->erro_sql = " Campo Data nao Informado.";
         $this->erro_campo = "w15_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["w15_data_dia"])){ 
         $sql  .= $virgula." w15_data = null ";
         $virgula = ",";
         if(trim($this->w15_data) == null ){ 
           $this->erro_sql = " Campo Data nao Informado.";
           $this->erro_campo = "w15_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     $sql .= " where ";
     if($w15_sequencial!=null){
       $sql .= " w15_sequencial = $this->w15_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->w15_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,9131,'$this->w15_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["w15_sequencial"]))
           $resac = db_query("insert into db_acount values($acount,1563,9131,'".AddSlashes(pg_result($resaco,$conresaco,'w15_sequencial'))."','$this->w15_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["w15_dai"]))
           $resac = db_query("insert into db_acount values($acount,1563,9132,'".AddSlashes(pg_result($resaco,$conresaco,'w15_dai'))."','$this->w15_dai',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["w15_mes"]))
           $resac = db_query("insert into db_acount values($acount,1563,9133,'".AddSlashes(pg_result($resaco,$conresaco,'w15_mes'))."','$this->w15_mes',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["w15_valreceita"]))
           $resac = db_query("insert into db_acount values($acount,1563,9135,'".AddSlashes(pg_result($resaco,$conresaco,'w15_valreceita'))."','$this->w15_valreceita',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["w15_cnpj"]))
           $resac = db_query("insert into db_acount values($acount,1563,9137,'".AddSlashes(pg_result($resaco,$conresaco,'w15_cnpj'))."','$this->w15_cnpj',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["w15_nota"]))
           $resac = db_query("insert into db_acount values($acount,1563,9175,'".AddSlashes(pg_result($resaco,$conresaco,'w15_nota'))."','$this->w15_nota',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["w15_serie"]))
           $resac = db_query("insert into db_acount values($acount,1563,9176,'".AddSlashes(pg_result($resaco,$conresaco,'w15_serie'))."','$this->w15_serie',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["w15_data"]))
           $resac = db_query("insert into db_acount values($acount,1563,9177,'".AddSlashes(pg_result($resaco,$conresaco,'w15_data'))."','$this->w15_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Valores retidos de minha empresa nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->w15_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Valores retidos de minha empresa nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->w15_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->w15_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($w15_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($w15_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,9131,'$w15_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,1563,9131,'','".AddSlashes(pg_result($resaco,$iresaco,'w15_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1563,9132,'','".AddSlashes(pg_result($resaco,$iresaco,'w15_dai'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1563,9133,'','".AddSlashes(pg_result($resaco,$iresaco,'w15_mes'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1563,9135,'','".AddSlashes(pg_result($resaco,$iresaco,'w15_valreceita'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1563,9137,'','".AddSlashes(pg_result($resaco,$iresaco,'w15_cnpj'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1563,9175,'','".AddSlashes(pg_result($resaco,$iresaco,'w15_nota'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1563,9176,'','".AddSlashes(pg_result($resaco,$iresaco,'w15_serie'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1563,9177,'','".AddSlashes(pg_result($resaco,$iresaco,'w15_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from db_dairetido
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($w15_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " w15_sequencial = $w15_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Valores retidos de minha empresa nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$w15_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Valores retidos de minha empresa nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$w15_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$w15_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:db_dairetido";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $w15_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from db_dairetido ";
     $sql .= "      inner join db_dae  on  db_dae.w04_codigo = db_dairetido.w15_dai";
     $sql2 = "";
     if($dbwhere==""){
       if($w15_sequencial!=null ){
         $sql2 .= " where db_dairetido.w15_sequencial = $w15_sequencial "; 
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
   function sql_query_file ( $w15_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from db_dairetido ";
     $sql2 = "";
     if($dbwhere==""){
       if($w15_sequencial!=null ){
         $sql2 .= " where db_dairetido.w15_sequencial = $w15_sequencial "; 
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
