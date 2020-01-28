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

//MODULO: contrib
//CLASSE DA ENTIDADE editalserv
class cl_editalserv { 
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
   var $d04_contri = 0; 
   var $d04_tipos = 0; 
   var $d04_quant = 0; 
   var $d04_vlrcal = 0; 
   var $d04_vlrval = 0; 
   var $d04_mult = 0; 
   var $d04_forma = 0; 
   var $d04_vlrobra = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 d04_contri = int4 = Constribuicao 
                 d04_tipos = int4 = Tipo de Serviço 
                 d04_quant = float8 = Quantidade 
                 d04_vlrcal = float8 = Valor para calculo 
                 d04_vlrval = float8 = Valor para valorização 
                 d04_mult = float8 = Multiplicador 
                 d04_forma = int4 = Forma de calculo 
                 d04_vlrobra = int4 = Valor da Obra 
                 ";
   //funcao construtor da classe 
   function cl_editalserv() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("editalserv"); 
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
       $this->d04_contri = ($this->d04_contri == ""?@$GLOBALS["HTTP_POST_VARS"]["d04_contri"]:$this->d04_contri);
       $this->d04_tipos = ($this->d04_tipos == ""?@$GLOBALS["HTTP_POST_VARS"]["d04_tipos"]:$this->d04_tipos);
       $this->d04_quant = ($this->d04_quant == ""?@$GLOBALS["HTTP_POST_VARS"]["d04_quant"]:$this->d04_quant);
       $this->d04_vlrcal = ($this->d04_vlrcal == ""?@$GLOBALS["HTTP_POST_VARS"]["d04_vlrcal"]:$this->d04_vlrcal);
       $this->d04_vlrval = ($this->d04_vlrval == ""?@$GLOBALS["HTTP_POST_VARS"]["d04_vlrval"]:$this->d04_vlrval);
       $this->d04_mult = ($this->d04_mult == ""?@$GLOBALS["HTTP_POST_VARS"]["d04_mult"]:$this->d04_mult);
       $this->d04_forma = ($this->d04_forma == ""?@$GLOBALS["HTTP_POST_VARS"]["d04_forma"]:$this->d04_forma);
       $this->d04_vlrobra = ($this->d04_vlrobra == ""?@$GLOBALS["HTTP_POST_VARS"]["d04_vlrobra"]:$this->d04_vlrobra);
     }else{
       $this->d04_contri = ($this->d04_contri == ""?@$GLOBALS["HTTP_POST_VARS"]["d04_contri"]:$this->d04_contri);
       $this->d04_tipos = ($this->d04_tipos == ""?@$GLOBALS["HTTP_POST_VARS"]["d04_tipos"]:$this->d04_tipos);
     }
   }
   // funcao para inclusao
   function incluir ($d04_contri,$d04_tipos){ 
      $this->atualizacampos();
     if($this->d04_quant == null ){ 
       $this->erro_sql = " Campo Quantidade nao Informado.";
       $this->erro_campo = "d04_quant";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->d04_vlrcal == null ){ 
       $this->erro_sql = " Campo Valor para calculo nao Informado.";
       $this->erro_campo = "d04_vlrcal";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->d04_vlrval == null ){ 
       $this->erro_sql = " Campo Valor para valorização nao Informado.";
       $this->erro_campo = "d04_vlrval";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->d04_mult == null ){ 
       $this->erro_sql = " Campo Multiplicador nao Informado.";
       $this->erro_campo = "d04_mult";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->d04_forma == null ){ 
       $this->erro_sql = " Campo Forma de calculo nao Informado.";
       $this->erro_campo = "d04_forma";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->d04_vlrobra == null ){ 
       $this->erro_sql = " Campo Valor da Obra nao Informado.";
       $this->erro_campo = "d04_vlrobra";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->d04_contri = $d04_contri; 
       $this->d04_tipos = $d04_tipos; 
     if(($this->d04_contri == null) || ($this->d04_contri == "") ){ 
       $this->erro_sql = " Campo d04_contri nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->d04_tipos == null) || ($this->d04_tipos == "") ){ 
       $this->erro_sql = " Campo d04_tipos nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into editalserv(
                                       d04_contri 
                                      ,d04_tipos 
                                      ,d04_quant 
                                      ,d04_vlrcal 
                                      ,d04_vlrval 
                                      ,d04_mult 
                                      ,d04_forma 
                                      ,d04_vlrobra 
                       )
                values (
                                $this->d04_contri 
                               ,$this->d04_tipos 
                               ,$this->d04_quant 
                               ,$this->d04_vlrcal 
                               ,$this->d04_vlrval 
                               ,$this->d04_mult 
                               ,$this->d04_forma 
                               ,$this->d04_vlrobra 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = " ($this->d04_contri."-".$this->d04_tipos) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = " já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = " ($this->d04_contri."-".$this->d04_tipos) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->d04_contri."-".$this->d04_tipos;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->d04_contri,$this->d04_tipos));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,695,'$this->d04_contri','I')");
       $resac = db_query("insert into db_acountkey values($acount,696,'$this->d04_tipos','I')");
       $resac = db_query("insert into db_acount values($acount,129,695,'','".AddSlashes(pg_result($resaco,0,'d04_contri'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,129,696,'','".AddSlashes(pg_result($resaco,0,'d04_tipos'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,129,697,'','".AddSlashes(pg_result($resaco,0,'d04_quant'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,129,698,'','".AddSlashes(pg_result($resaco,0,'d04_vlrcal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,129,6011,'','".AddSlashes(pg_result($resaco,0,'d04_vlrval'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,129,8647,'','".AddSlashes(pg_result($resaco,0,'d04_mult'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,129,9558,'','".AddSlashes(pg_result($resaco,0,'d04_forma'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,129,11001,'','".AddSlashes(pg_result($resaco,0,'d04_vlrobra'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($d04_contri=null,$d04_tipos=null) { 
      $this->atualizacampos();
     $sql = " update editalserv set ";
     $virgula = "";
     if(trim($this->d04_contri)!="" || isset($GLOBALS["HTTP_POST_VARS"]["d04_contri"])){ 
       $sql  .= $virgula." d04_contri = $this->d04_contri ";
       $virgula = ",";
       if(trim($this->d04_contri) == null ){ 
         $this->erro_sql = " Campo Constribuicao nao Informado.";
         $this->erro_campo = "d04_contri";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->d04_tipos)!="" || isset($GLOBALS["HTTP_POST_VARS"]["d04_tipos"])){ 
       $sql  .= $virgula." d04_tipos = $this->d04_tipos ";
       $virgula = ",";
       if(trim($this->d04_tipos) == null ){ 
         $this->erro_sql = " Campo Tipo de Serviço nao Informado.";
         $this->erro_campo = "d04_tipos";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->d04_quant)!="" || isset($GLOBALS["HTTP_POST_VARS"]["d04_quant"])){ 
       $sql  .= $virgula." d04_quant = $this->d04_quant ";
       $virgula = ",";
       if(trim($this->d04_quant) == null ){ 
         $this->erro_sql = " Campo Quantidade nao Informado.";
         $this->erro_campo = "d04_quant";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->d04_vlrcal)!="" || isset($GLOBALS["HTTP_POST_VARS"]["d04_vlrcal"])){ 
       $sql  .= $virgula." d04_vlrcal = $this->d04_vlrcal ";
       $virgula = ",";
       if(trim($this->d04_vlrcal) == null ){ 
         $this->erro_sql = " Campo Valor para calculo nao Informado.";
         $this->erro_campo = "d04_vlrcal";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->d04_vlrval)!="" || isset($GLOBALS["HTTP_POST_VARS"]["d04_vlrval"])){ 
       $sql  .= $virgula." d04_vlrval = $this->d04_vlrval ";
       $virgula = ",";
       if(trim($this->d04_vlrval) == null ){ 
         $this->erro_sql = " Campo Valor para valorização nao Informado.";
         $this->erro_campo = "d04_vlrval";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->d04_mult)!="" || isset($GLOBALS["HTTP_POST_VARS"]["d04_mult"])){ 
       $sql  .= $virgula." d04_mult = $this->d04_mult ";
       $virgula = ",";
       if(trim($this->d04_mult) == null ){ 
         $this->erro_sql = " Campo Multiplicador nao Informado.";
         $this->erro_campo = "d04_mult";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->d04_forma)!="" || isset($GLOBALS["HTTP_POST_VARS"]["d04_forma"])){ 
       $sql  .= $virgula." d04_forma = $this->d04_forma ";
       $virgula = ",";
       if(trim($this->d04_forma) == null ){ 
         $this->erro_sql = " Campo Forma de calculo nao Informado.";
         $this->erro_campo = "d04_forma";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->d04_vlrobra)!="" || isset($GLOBALS["HTTP_POST_VARS"]["d04_vlrobra"])){ 
       $sql  .= $virgula." d04_vlrobra = $this->d04_vlrobra ";
       $virgula = ",";
       if(trim($this->d04_vlrobra) == null ){ 
         $this->erro_sql = " Campo Valor da Obra nao Informado.";
         $this->erro_campo = "d04_vlrobra";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($d04_contri!=null){
       $sql .= " d04_contri = $this->d04_contri";
     }
     if($d04_tipos!=null){
       $sql .= " and  d04_tipos = $this->d04_tipos";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->d04_contri,$this->d04_tipos));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,695,'$this->d04_contri','A')");
         $resac = db_query("insert into db_acountkey values($acount,696,'$this->d04_tipos','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["d04_contri"]))
           $resac = db_query("insert into db_acount values($acount,129,695,'".AddSlashes(pg_result($resaco,$conresaco,'d04_contri'))."','$this->d04_contri',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["d04_tipos"]))
           $resac = db_query("insert into db_acount values($acount,129,696,'".AddSlashes(pg_result($resaco,$conresaco,'d04_tipos'))."','$this->d04_tipos',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["d04_quant"]))
           $resac = db_query("insert into db_acount values($acount,129,697,'".AddSlashes(pg_result($resaco,$conresaco,'d04_quant'))."','$this->d04_quant',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["d04_vlrcal"]))
           $resac = db_query("insert into db_acount values($acount,129,698,'".AddSlashes(pg_result($resaco,$conresaco,'d04_vlrcal'))."','$this->d04_vlrcal',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["d04_vlrval"]))
           $resac = db_query("insert into db_acount values($acount,129,6011,'".AddSlashes(pg_result($resaco,$conresaco,'d04_vlrval'))."','$this->d04_vlrval',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["d04_mult"]))
           $resac = db_query("insert into db_acount values($acount,129,8647,'".AddSlashes(pg_result($resaco,$conresaco,'d04_mult'))."','$this->d04_mult',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["d04_forma"]))
           $resac = db_query("insert into db_acount values($acount,129,9558,'".AddSlashes(pg_result($resaco,$conresaco,'d04_forma'))."','$this->d04_forma',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["d04_vlrobra"]))
           $resac = db_query("insert into db_acount values($acount,129,11001,'".AddSlashes(pg_result($resaco,$conresaco,'d04_vlrobra'))."','$this->d04_vlrobra',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = " nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->d04_contri."-".$this->d04_tipos;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = " nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->d04_contri."-".$this->d04_tipos;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->d04_contri."-".$this->d04_tipos;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($d04_contri=null,$d04_tipos=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($d04_contri,$d04_tipos));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,695,'$d04_contri','E')");
         $resac = db_query("insert into db_acountkey values($acount,696,'$d04_tipos','E')");
         $resac = db_query("insert into db_acount values($acount,129,695,'','".AddSlashes(pg_result($resaco,$iresaco,'d04_contri'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,129,696,'','".AddSlashes(pg_result($resaco,$iresaco,'d04_tipos'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,129,697,'','".AddSlashes(pg_result($resaco,$iresaco,'d04_quant'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,129,698,'','".AddSlashes(pg_result($resaco,$iresaco,'d04_vlrcal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,129,6011,'','".AddSlashes(pg_result($resaco,$iresaco,'d04_vlrval'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,129,8647,'','".AddSlashes(pg_result($resaco,$iresaco,'d04_mult'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,129,9558,'','".AddSlashes(pg_result($resaco,$iresaco,'d04_forma'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,129,11001,'','".AddSlashes(pg_result($resaco,$iresaco,'d04_vlrobra'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from editalserv
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($d04_contri != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " d04_contri = $d04_contri ";
        }
        if($d04_tipos != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " d04_tipos = $d04_tipos ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = " nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$d04_contri."-".$d04_tipos;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = " nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$d04_contri."-".$d04_tipos;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$d04_contri."-".$d04_tipos;
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
        $this->erro_sql   = "Record Vazio na Tabela:editalserv";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $d04_contri=null,$d04_tipos=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from editalserv ";
     $sql .= "      inner join editalrua    on  editalrua.d02_contri = editalserv.d04_contri";
     $sql .= "      inner join editaltipo   on  editaltipo.d03_tipos = editalserv.d04_tipos";
     $sql .= "      inner join ruas         on  ruas.j14_codigo = editalrua.d02_codigo";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = editalrua.d02_idlog";
     $sql .= "      inner join edital       on  edital.d01_codedi = editalrua.d02_codedi";
     $sql2 = "";
     if($dbwhere==""){
       if($d04_contri!=null ){
         $sql2 .= " where editalserv.d04_contri = $d04_contri "; 
       } 
       if($d04_tipos!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " editalserv.d04_tipos = $d04_tipos "; 
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
   function sql_query_file ( $d04_contri=null,$d04_tipos=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from editalserv ";
     $sql2 = "";
     if($dbwhere==""){
       if($d04_contri!=null ){
         $sql2 .= " where editalserv.d04_contri = $d04_contri "; 
       } 
       if($d04_tipos!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " editalserv.d04_tipos = $d04_tipos "; 
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