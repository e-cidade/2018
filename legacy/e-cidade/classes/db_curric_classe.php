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

//MODULO: recursoshumanos
//CLASSE DA ENTIDADE curric
class cl_curric { 
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
   var $h03_seq = 0; 
   var $h03_numcgm = 0; 
   var $h03_data_dia = null; 
   var $h03_data_mes = null; 
   var $h03_data_ano = null; 
   var $h03_data = null; 
   var $h03_codigo = 0; 
   var $h03_descr = null; 
   var $h03_detalh = null; 
   var $h03_tipopartic = 0; 
   var $h03_cargahoraria = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 h03_seq = int4 = Sequencia do Evento 
                 h03_numcgm = int4 = Funcionario 
                 h03_data = date = Data do Evento 
                 h03_codigo = int4 = Codigo 
                 h03_descr = varchar(40) = Descrição 
                 h03_detalh = text = Observacao do Evento 
                 h03_tipopartic = int4 = Evento 
                 h03_cargahoraria = numeric(10) = Carga horária do curso 
                 ";
   //funcao construtor da classe 
   function cl_curric() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("curric"); 
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
       $this->h03_seq = ($this->h03_seq == ""?@$GLOBALS["HTTP_POST_VARS"]["h03_seq"]:$this->h03_seq);
       $this->h03_numcgm = ($this->h03_numcgm == ""?@$GLOBALS["HTTP_POST_VARS"]["h03_numcgm"]:$this->h03_numcgm);
       if($this->h03_data == ""){
         $this->h03_data_dia = ($this->h03_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["h03_data_dia"]:$this->h03_data_dia);
         $this->h03_data_mes = ($this->h03_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["h03_data_mes"]:$this->h03_data_mes);
         $this->h03_data_ano = ($this->h03_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["h03_data_ano"]:$this->h03_data_ano);
         if($this->h03_data_dia != ""){
            $this->h03_data = $this->h03_data_ano."-".$this->h03_data_mes."-".$this->h03_data_dia;
         }
       }
       $this->h03_codigo = ($this->h03_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["h03_codigo"]:$this->h03_codigo);
       $this->h03_descr = ($this->h03_descr == ""?@$GLOBALS["HTTP_POST_VARS"]["h03_descr"]:$this->h03_descr);
       $this->h03_detalh = ($this->h03_detalh == ""?@$GLOBALS["HTTP_POST_VARS"]["h03_detalh"]:$this->h03_detalh);
       $this->h03_tipopartic = ($this->h03_tipopartic == ""?@$GLOBALS["HTTP_POST_VARS"]["h03_tipopartic"]:$this->h03_tipopartic);
       $this->h03_cargahoraria = ($this->h03_cargahoraria == ""?@$GLOBALS["HTTP_POST_VARS"]["h03_cargahoraria"]:$this->h03_cargahoraria);
     }else{
       $this->h03_seq = ($this->h03_seq == ""?@$GLOBALS["HTTP_POST_VARS"]["h03_seq"]:$this->h03_seq);
     }
   }
   // funcao para inclusao
   function incluir ($h03_seq){ 
      $this->atualizacampos();
     if($this->h03_numcgm == null ){ 
       $this->erro_sql = " Campo Funcionario nao Informado.";
       $this->erro_campo = "h03_numcgm";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->h03_data == null ){ 
       $this->erro_sql = " Campo Data do Evento nao Informado.";
       $this->erro_campo = "h03_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->h03_codigo == null ){ 
       $this->erro_sql = " Campo Codigo nao Informado.";
       $this->erro_campo = "h03_codigo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->h03_descr == null ){ 
       $this->erro_sql = " Campo Descrição nao Informado.";
       $this->erro_campo = "h03_descr";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->h03_detalh == null ){ 
       $this->erro_sql = " Campo Observacao do Evento nao Informado.";
       $this->erro_campo = "h03_detalh";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->h03_tipopartic == null ){ 
       $this->erro_sql = " Campo Evento nao Informado.";
       $this->erro_campo = "h03_tipopartic";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->h03_cargahoraria == null ){ 
       $this->erro_sql = " Campo Carga horária do curso nao Informado.";
       $this->erro_campo = "h03_cargahoraria";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($h03_seq == "" || $h03_seq == null ){
       $result = db_query("select nextval('curric_h03_seq_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: curric_h03_seq_seq do campo: h03_seq"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->h03_seq = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from curric_h03_seq_seq");
       if(($result != false) && (pg_result($result,0,0) < $h03_seq)){
         $this->erro_sql = " Campo h03_seq maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->h03_seq = $h03_seq; 
       }
     }
     if(($this->h03_seq == null) || ($this->h03_seq == "") ){ 
       $this->erro_sql = " Campo h03_seq nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into curric(
                                       h03_seq 
                                      ,h03_numcgm 
                                      ,h03_data 
                                      ,h03_codigo 
                                      ,h03_descr 
                                      ,h03_detalh 
                                      ,h03_tipopartic 
                                      ,h03_cargahoraria 
                       )
                values (
                                $this->h03_seq 
                               ,$this->h03_numcgm 
                               ,".($this->h03_data == "null" || $this->h03_data == ""?"null":"'".$this->h03_data."'")." 
                               ,$this->h03_codigo 
                               ,'$this->h03_descr' 
                               ,'$this->h03_detalh' 
                               ,$this->h03_tipopartic 
                               ,$this->h03_cargahoraria 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Contem as informacoes curriculares do   funcionari ($this->h03_seq) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Contem as informacoes curriculares do   funcionari já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Contem as informacoes curriculares do   funcionari ($this->h03_seq) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->h03_seq;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->h03_seq));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,3864,'$this->h03_seq','I')");
       $resac = db_query("insert into db_acount values($acount,543,3864,'','".AddSlashes(pg_result($resaco,0,'h03_seq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,543,3863,'','".AddSlashes(pg_result($resaco,0,'h03_numcgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,543,3865,'','".AddSlashes(pg_result($resaco,0,'h03_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,543,3866,'','".AddSlashes(pg_result($resaco,0,'h03_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,543,3867,'','".AddSlashes(pg_result($resaco,0,'h03_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,543,3869,'','".AddSlashes(pg_result($resaco,0,'h03_detalh'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,543,14435,'','".AddSlashes(pg_result($resaco,0,'h03_tipopartic'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,543,18737,'','".AddSlashes(pg_result($resaco,0,'h03_cargahoraria'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($h03_seq=null) { 
      $this->atualizacampos();
     $sql = " update curric set ";
     $virgula = "";
     if(trim($this->h03_seq)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h03_seq"])){ 
       $sql  .= $virgula." h03_seq = $this->h03_seq ";
       $virgula = ",";
       if(trim($this->h03_seq) == null ){ 
         $this->erro_sql = " Campo Sequencia do Evento nao Informado.";
         $this->erro_campo = "h03_seq";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->h03_numcgm)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h03_numcgm"])){ 
       $sql  .= $virgula." h03_numcgm = $this->h03_numcgm ";
       $virgula = ",";
       if(trim($this->h03_numcgm) == null ){ 
         $this->erro_sql = " Campo Funcionario nao Informado.";
         $this->erro_campo = "h03_numcgm";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->h03_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h03_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["h03_data_dia"] !="") ){ 
       $sql  .= $virgula." h03_data = '$this->h03_data' ";
       $virgula = ",";
       if(trim($this->h03_data) == null ){ 
         $this->erro_sql = " Campo Data do Evento nao Informado.";
         $this->erro_campo = "h03_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["h03_data_dia"])){ 
         $sql  .= $virgula." h03_data = null ";
         $virgula = ",";
         if(trim($this->h03_data) == null ){ 
           $this->erro_sql = " Campo Data do Evento nao Informado.";
           $this->erro_campo = "h03_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->h03_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h03_codigo"])){ 
       $sql  .= $virgula." h03_codigo = $this->h03_codigo ";
       $virgula = ",";
       if(trim($this->h03_codigo) == null ){ 
         $this->erro_sql = " Campo Codigo nao Informado.";
         $this->erro_campo = "h03_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->h03_descr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h03_descr"])){ 
       $sql  .= $virgula." h03_descr = '$this->h03_descr' ";
       $virgula = ",";
       if(trim($this->h03_descr) == null ){ 
         $this->erro_sql = " Campo Descrição nao Informado.";
         $this->erro_campo = "h03_descr";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->h03_detalh)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h03_detalh"])){ 
       $sql  .= $virgula." h03_detalh = '$this->h03_detalh' ";
       $virgula = ",";
       if(trim($this->h03_detalh) == null ){ 
         $this->erro_sql = " Campo Observacao do Evento nao Informado.";
         $this->erro_campo = "h03_detalh";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->h03_tipopartic)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h03_tipopartic"])){ 
       $sql  .= $virgula." h03_tipopartic = $this->h03_tipopartic ";
       $virgula = ",";
       if(trim($this->h03_tipopartic) == null ){ 
         $this->erro_sql = " Campo Evento nao Informado.";
         $this->erro_campo = "h03_tipopartic";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->h03_cargahoraria)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h03_cargahoraria"])){ 
       $sql  .= $virgula." h03_cargahoraria = $this->h03_cargahoraria ";
       $virgula = ",";
       if(trim($this->h03_cargahoraria) == null ){ 
         $this->erro_sql = " Campo Carga horária do curso nao Informado.";
         $this->erro_campo = "h03_cargahoraria";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($h03_seq!=null){
       $sql .= " h03_seq = $this->h03_seq";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->h03_seq));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,3864,'$this->h03_seq','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h03_seq"]) || $this->h03_seq != "")
           $resac = db_query("insert into db_acount values($acount,543,3864,'".AddSlashes(pg_result($resaco,$conresaco,'h03_seq'))."','$this->h03_seq',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h03_numcgm"]) || $this->h03_numcgm != "")
           $resac = db_query("insert into db_acount values($acount,543,3863,'".AddSlashes(pg_result($resaco,$conresaco,'h03_numcgm'))."','$this->h03_numcgm',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h03_data"]) || $this->h03_data != "")
           $resac = db_query("insert into db_acount values($acount,543,3865,'".AddSlashes(pg_result($resaco,$conresaco,'h03_data'))."','$this->h03_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h03_codigo"]) || $this->h03_codigo != "")
           $resac = db_query("insert into db_acount values($acount,543,3866,'".AddSlashes(pg_result($resaco,$conresaco,'h03_codigo'))."','$this->h03_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h03_descr"]) || $this->h03_descr != "")
           $resac = db_query("insert into db_acount values($acount,543,3867,'".AddSlashes(pg_result($resaco,$conresaco,'h03_descr'))."','$this->h03_descr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h03_detalh"]) || $this->h03_detalh != "")
           $resac = db_query("insert into db_acount values($acount,543,3869,'".AddSlashes(pg_result($resaco,$conresaco,'h03_detalh'))."','$this->h03_detalh',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h03_tipopartic"]) || $this->h03_tipopartic != "")
           $resac = db_query("insert into db_acount values($acount,543,14435,'".AddSlashes(pg_result($resaco,$conresaco,'h03_tipopartic'))."','$this->h03_tipopartic',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h03_cargahoraria"]) || $this->h03_cargahoraria != "")
           $resac = db_query("insert into db_acount values($acount,543,18737,'".AddSlashes(pg_result($resaco,$conresaco,'h03_cargahoraria'))."','$this->h03_cargahoraria',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Contem as informacoes curriculares do   funcionari nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->h03_seq;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Contem as informacoes curriculares do   funcionari nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->h03_seq;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->h03_seq;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($h03_seq=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($h03_seq));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,3864,'$h03_seq','E')");
         $resac = db_query("insert into db_acount values($acount,543,3864,'','".AddSlashes(pg_result($resaco,$iresaco,'h03_seq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,543,3863,'','".AddSlashes(pg_result($resaco,$iresaco,'h03_numcgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,543,3865,'','".AddSlashes(pg_result($resaco,$iresaco,'h03_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,543,3866,'','".AddSlashes(pg_result($resaco,$iresaco,'h03_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,543,3867,'','".AddSlashes(pg_result($resaco,$iresaco,'h03_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,543,3869,'','".AddSlashes(pg_result($resaco,$iresaco,'h03_detalh'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,543,14435,'','".AddSlashes(pg_result($resaco,$iresaco,'h03_tipopartic'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,543,18737,'','".AddSlashes(pg_result($resaco,$iresaco,'h03_cargahoraria'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from curric
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($h03_seq != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " h03_seq = $h03_seq ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Contem as informacoes curriculares do   funcionari nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$h03_seq;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Contem as informacoes curriculares do   funcionari nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$h03_seq;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$h03_seq;
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
        $this->erro_sql   = "Record Vazio na Tabela:curric";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $h03_seq=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from curric                                                                      ";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = curric.h03_numcgm                      ";
     $sql .= "      inner join tabcurri  on  tabcurri.h01_codigo = curric.h03_codigo            ";
     $sql .= "      inner join tabcurritipo  on  tabcurritipo.h02_codigo = tabcurri.h01_codtipo ";
     $sql2 = "";
     if($dbwhere==""){
       if($h03_seq!=null ){
         $sql2 .= " where curric.h03_seq = $h03_seq "; 
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
   function sql_query_file ( $h03_seq=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from curric ";
     $sql2 = "";
     if($dbwhere==""){
       if($h03_seq!=null ){
         $sql2 .= " where curric.h03_seq = $h03_seq "; 
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
  
  
/*
 * criado metodo externo para correção dos join
 */  
   function sql_query_curric ( $h03_seq=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from curric ";
     $sql .= "      inner join cgm as c  on  c.z01_numcgm = curric.h03_numcgm";
     $sql .= "      inner join tabcurri  on  tabcurri.h01_codigo = curric.h03_codigo";
     $sql .= "      inner join cgm as e  on  e.z01_numcgm = tabcurri.h01_cgmentid";
     $sql .= "      inner join tabcurritipo  on  tabcurritipo.h02_codigo = tabcurri.h01_codtipo";
     $sql2 = "";
     if($dbwhere==""){
       if($h03_seq!=null ){
         $sql2 .= " where curric.h03_seq = $h03_seq "; 
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
  
   function sql_query_cursos ($iCodigoPromocao) {
     
     $sSql  = " select h72_sequencial,                                                                  ";
     $sSql .= "        h03_seq,                                                                         ";
     $sSql .= "        h03_descr,                                                                       ";
     $sSql .= "        h03_cargahoraria,                                                                ";
     $sSql .= "        h03_data,                                                                        ";
     $sSql .= "        case when (h74_sequencial is not null or h75_sequencial is not null)             ";
     $sSql .= "             then false                                                                  ";
     $sSql .= "             else true                                                                   ";
     $sSql .= "        end  as habilitado                                                               ";
     $sSql .= "   from rhpessoal                                                                        ";
     $sSql .= "        inner join rhpromocao                    on h72_regist          = rh01_regist    ";
     $sSql .= "                                                and h72_ativo          is true           ";
     $sSql .= "        inner join curric                        on h03_numcgm          = rh01_numcgm    ";
     $sSql .= "        left  join rhpromocaocurso               on h74_rhpromocao      = h72_sequencial ";
     $sSql .= "                                                and h74_rhcurso         = h03_seq        ";
     $sSql .= "        left  join rhpromocaocursosavaliacao     on h75_rhpromocaocurso = h74_sequencial ";
     $sSql .= "  where h72_sequencial = {$iCodigoPromocao}                                              ";
     $sSql .= "    and h03_data >= h72_dtinicial                                                        ";
    
     return $sSql;
      
   }
}
?>