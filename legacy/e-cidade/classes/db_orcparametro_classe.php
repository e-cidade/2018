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

//MODULO: orcamento
//CLASSE DA ENTIDADE orcparametro
class cl_orcparametro { 
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
   var $o50_anousu = 0; 
   var $o50_coddot = 0; 
   var $o50_subelem = 'f'; 
   var $o50_programa = 0; 
   var $o50_estrutdespesa = null; 
   var $o50_estrutelemento = null; 
   var $o50_estrutreceita = null; 
   var $o50_tipoproj = null; 
   var $o50_utilizapacto = 'f'; 
   var $o50_liberadecimalppa = 'f'; 
   var $o50_estruturarecurso = 0; 
   var $o50_estruturacp = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 o50_anousu = int4 = Exerc�cio 
                 o50_coddot = int4 = �ltimo C�digo 
                 o50_subelem = bool = Usa Sub-Elemento 
                 o50_programa = int4 = Ultimo c�digo de programas 
                 o50_estrutdespesa = varchar(50) = Estrutural Despesa 
                 o50_estrutelemento = varchar(50) = Estrutural Elemento 
                 o50_estrutreceita = varchar(50) = Estrutural Receita 
                 o50_tipoproj = char(1) = Modelo Prj/Decreto 
                 o50_utilizapacto = bool = Utiliza Pactos 
                 o50_liberadecimalppa = bool = Utiliza centavos no PPA/Or�amento 
                 o50_estruturarecurso = int4 = C�digo Estrutura Recurso 
                 o50_estruturacp = int4 = C�digo Estrutura 
                 ";
   //funcao construtor da classe 
   function cl_orcparametro() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("orcparametro"); 
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
       $this->o50_anousu = ($this->o50_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["o50_anousu"]:$this->o50_anousu);
       $this->o50_coddot = ($this->o50_coddot == ""?@$GLOBALS["HTTP_POST_VARS"]["o50_coddot"]:$this->o50_coddot);
       $this->o50_subelem = ($this->o50_subelem == "f"?@$GLOBALS["HTTP_POST_VARS"]["o50_subelem"]:$this->o50_subelem);
       $this->o50_programa = ($this->o50_programa == ""?@$GLOBALS["HTTP_POST_VARS"]["o50_programa"]:$this->o50_programa);
       $this->o50_estrutdespesa = ($this->o50_estrutdespesa == ""?@$GLOBALS["HTTP_POST_VARS"]["o50_estrutdespesa"]:$this->o50_estrutdespesa);
       $this->o50_estrutelemento = ($this->o50_estrutelemento == ""?@$GLOBALS["HTTP_POST_VARS"]["o50_estrutelemento"]:$this->o50_estrutelemento);
       $this->o50_estrutreceita = ($this->o50_estrutreceita == ""?@$GLOBALS["HTTP_POST_VARS"]["o50_estrutreceita"]:$this->o50_estrutreceita);
       $this->o50_tipoproj = ($this->o50_tipoproj == ""?@$GLOBALS["HTTP_POST_VARS"]["o50_tipoproj"]:$this->o50_tipoproj);
       $this->o50_utilizapacto = ($this->o50_utilizapacto == "f"?@$GLOBALS["HTTP_POST_VARS"]["o50_utilizapacto"]:$this->o50_utilizapacto);
       $this->o50_liberadecimalppa = ($this->o50_liberadecimalppa == "f"?@$GLOBALS["HTTP_POST_VARS"]["o50_liberadecimalppa"]:$this->o50_liberadecimalppa);
       $this->o50_estruturarecurso = ($this->o50_estruturarecurso == ""?@$GLOBALS["HTTP_POST_VARS"]["o50_estruturarecurso"]:$this->o50_estruturarecurso);
       $this->o50_estruturacp = ($this->o50_estruturacp == ""?@$GLOBALS["HTTP_POST_VARS"]["o50_estruturacp"]:$this->o50_estruturacp);
     }else{
       $this->o50_anousu = ($this->o50_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["o50_anousu"]:$this->o50_anousu);
     }
   }
   // funcao para inclusao
   function incluir ($o50_anousu){ 
      $this->atualizacampos();
     if($this->o50_coddot == null ){ 
       $this->erro_sql = " Campo �ltimo C�digo nao Informado.";
       $this->erro_campo = "o50_coddot";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o50_subelem == null ){ 
       $this->erro_sql = " Campo Usa Sub-Elemento nao Informado.";
       $this->erro_campo = "o50_subelem";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o50_programa == null ){ 
       $this->erro_sql = " Campo Ultimo c�digo de programas nao Informado.";
       $this->erro_campo = "o50_programa";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o50_estrutdespesa == null ){ 
       $this->erro_sql = " Campo Estrutural Despesa nao Informado.";
       $this->erro_campo = "o50_estrutdespesa";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o50_estrutelemento == null ){ 
       $this->erro_sql = " Campo Estrutural Elemento nao Informado.";
       $this->erro_campo = "o50_estrutelemento";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o50_estrutreceita == null ){ 
       $this->erro_sql = " Campo Estrutural Receita nao Informado.";
       $this->erro_campo = "o50_estrutreceita";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o50_tipoproj == null ){ 
       $this->erro_sql = " Campo Modelo Prj/Decreto nao Informado.";
       $this->erro_campo = "o50_tipoproj";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o50_utilizapacto == null ){ 
       $this->erro_sql = " Campo Utiliza Pactos nao Informado.";
       $this->erro_campo = "o50_utilizapacto";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o50_liberadecimalppa == null ){ 
       $this->erro_sql = " Campo Utiliza centavos no PPA/Or�amento nao Informado.";
       $this->erro_campo = "o50_liberadecimalppa";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o50_estruturarecurso == null ){ 
       $this->erro_sql = " Campo C�digo Estrutura Recurso nao Informado.";
       $this->erro_campo = "o50_estruturarecurso";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o50_estruturacp == null ){ 
       $this->erro_sql = " Campo C�digo Estrutura nao Informado.";
       $this->erro_campo = "o50_estruturacp";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->o50_anousu = $o50_anousu; 
     if(($this->o50_anousu == null) || ($this->o50_anousu == "") ){ 
       $this->erro_sql = " Campo o50_anousu nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into orcparametro(
                                       o50_anousu 
                                      ,o50_coddot 
                                      ,o50_subelem 
                                      ,o50_programa 
                                      ,o50_estrutdespesa 
                                      ,o50_estrutelemento 
                                      ,o50_estrutreceita 
                                      ,o50_tipoproj 
                                      ,o50_utilizapacto 
                                      ,o50_liberadecimalppa 
                                      ,o50_estruturarecurso 
                                      ,o50_estruturacp 
                       )
                values (
                                $this->o50_anousu 
                               ,$this->o50_coddot 
                               ,'$this->o50_subelem' 
                               ,$this->o50_programa 
                               ,'$this->o50_estrutdespesa' 
                               ,'$this->o50_estrutelemento' 
                               ,'$this->o50_estrutreceita' 
                               ,'$this->o50_tipoproj' 
                               ,'$this->o50_utilizapacto' 
                               ,'$this->o50_liberadecimalppa' 
                               ,$this->o50_estruturarecurso 
                               ,$this->o50_estruturacp 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Par�metros do Or�amento ($this->o50_anousu) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Par�metros do Or�amento j� Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Par�metros do Or�amento ($this->o50_anousu) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->o50_anousu;
     $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->o50_anousu));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,5345,'$this->o50_anousu','I')");
       $resac = db_query("insert into db_acount values($acount,759,5345,'','".AddSlashes(pg_result($resaco,0,'o50_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,759,5346,'','".AddSlashes(pg_result($resaco,0,'o50_coddot'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,759,5347,'','".AddSlashes(pg_result($resaco,0,'o50_subelem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,759,5348,'','".AddSlashes(pg_result($resaco,0,'o50_programa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,759,5349,'','".AddSlashes(pg_result($resaco,0,'o50_estrutdespesa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,759,5351,'','".AddSlashes(pg_result($resaco,0,'o50_estrutelemento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,759,5350,'','".AddSlashes(pg_result($resaco,0,'o50_estrutreceita'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,759,9483,'','".AddSlashes(pg_result($resaco,0,'o50_tipoproj'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,759,14058,'','".AddSlashes(pg_result($resaco,0,'o50_utilizapacto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,759,15651,'','".AddSlashes(pg_result($resaco,0,'o50_liberadecimalppa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,759,18119,'','".AddSlashes(pg_result($resaco,0,'o50_estruturarecurso'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,759,18118,'','".AddSlashes(pg_result($resaco,0,'o50_estruturacp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($o50_anousu=null) { 
      $this->atualizacampos();
     $sql = " update orcparametro set ";
     $virgula = "";
     if(trim($this->o50_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o50_anousu"])){ 
       $sql  .= $virgula." o50_anousu = $this->o50_anousu ";
       $virgula = ",";
       if(trim($this->o50_anousu) == null ){ 
         $this->erro_sql = " Campo Exerc�cio nao Informado.";
         $this->erro_campo = "o50_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o50_coddot)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o50_coddot"])){ 
       $sql  .= $virgula." o50_coddot = $this->o50_coddot ";
       $virgula = ",";
       if(trim($this->o50_coddot) == null ){ 
         $this->erro_sql = " Campo �ltimo C�digo nao Informado.";
         $this->erro_campo = "o50_coddot";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o50_subelem)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o50_subelem"])){ 
       $sql  .= $virgula." o50_subelem = '$this->o50_subelem' ";
       $virgula = ",";
       if(trim($this->o50_subelem) == null ){ 
         $this->erro_sql = " Campo Usa Sub-Elemento nao Informado.";
         $this->erro_campo = "o50_subelem";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o50_programa)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o50_programa"])){ 
       $sql  .= $virgula." o50_programa = $this->o50_programa ";
       $virgula = ",";
       if(trim($this->o50_programa) == null ){ 
         $this->erro_sql = " Campo Ultimo c�digo de programas nao Informado.";
         $this->erro_campo = "o50_programa";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o50_estrutdespesa)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o50_estrutdespesa"])){ 
       $sql  .= $virgula." o50_estrutdespesa = '$this->o50_estrutdespesa' ";
       $virgula = ",";
       if(trim($this->o50_estrutdespesa) == null ){ 
         $this->erro_sql = " Campo Estrutural Despesa nao Informado.";
         $this->erro_campo = "o50_estrutdespesa";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o50_estrutelemento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o50_estrutelemento"])){ 
       $sql  .= $virgula." o50_estrutelemento = '$this->o50_estrutelemento' ";
       $virgula = ",";
       if(trim($this->o50_estrutelemento) == null ){ 
         $this->erro_sql = " Campo Estrutural Elemento nao Informado.";
         $this->erro_campo = "o50_estrutelemento";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o50_estrutreceita)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o50_estrutreceita"])){ 
       $sql  .= $virgula." o50_estrutreceita = '$this->o50_estrutreceita' ";
       $virgula = ",";
       if(trim($this->o50_estrutreceita) == null ){ 
         $this->erro_sql = " Campo Estrutural Receita nao Informado.";
         $this->erro_campo = "o50_estrutreceita";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o50_tipoproj)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o50_tipoproj"])){ 
       $sql  .= $virgula." o50_tipoproj = '$this->o50_tipoproj' ";
       $virgula = ",";
       if(trim($this->o50_tipoproj) == null ){ 
         $this->erro_sql = " Campo Modelo Prj/Decreto nao Informado.";
         $this->erro_campo = "o50_tipoproj";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o50_utilizapacto)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o50_utilizapacto"])){ 
       $sql  .= $virgula." o50_utilizapacto = '$this->o50_utilizapacto' ";
       $virgula = ",";
       if(trim($this->o50_utilizapacto) == null ){ 
         $this->erro_sql = " Campo Utiliza Pactos nao Informado.";
         $this->erro_campo = "o50_utilizapacto";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o50_liberadecimalppa)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o50_liberadecimalppa"])){ 
       $sql  .= $virgula." o50_liberadecimalppa = '$this->o50_liberadecimalppa' ";
       $virgula = ",";
       if(trim($this->o50_liberadecimalppa) == null ){ 
         $this->erro_sql = " Campo Utiliza centavos no PPA/Or�amento nao Informado.";
         $this->erro_campo = "o50_liberadecimalppa";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o50_estruturarecurso)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o50_estruturarecurso"])){ 
       $sql  .= $virgula." o50_estruturarecurso = $this->o50_estruturarecurso ";
       $virgula = ",";
       if(trim($this->o50_estruturarecurso) == null ){ 
         $this->erro_sql = " Campo C�digo Estrutura Recurso nao Informado.";
         $this->erro_campo = "o50_estruturarecurso";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o50_estruturacp)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o50_estruturacp"])){ 
       $sql  .= $virgula." o50_estruturacp = $this->o50_estruturacp ";
       $virgula = ",";
       if(trim($this->o50_estruturacp) == null ){ 
         $this->erro_sql = " Campo C�digo Estrutura nao Informado.";
         $this->erro_campo = "o50_estruturacp";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($o50_anousu!=null){
       $sql .= " o50_anousu = $this->o50_anousu";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->o50_anousu));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,5345,'$this->o50_anousu','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o50_anousu"]) || $this->o50_anousu != "")
           $resac = db_query("insert into db_acount values($acount,759,5345,'".AddSlashes(pg_result($resaco,$conresaco,'o50_anousu'))."','$this->o50_anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o50_coddot"]) || $this->o50_coddot != "")
           $resac = db_query("insert into db_acount values($acount,759,5346,'".AddSlashes(pg_result($resaco,$conresaco,'o50_coddot'))."','$this->o50_coddot',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o50_subelem"]) || $this->o50_subelem != "")
           $resac = db_query("insert into db_acount values($acount,759,5347,'".AddSlashes(pg_result($resaco,$conresaco,'o50_subelem'))."','$this->o50_subelem',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o50_programa"]) || $this->o50_programa != "")
           $resac = db_query("insert into db_acount values($acount,759,5348,'".AddSlashes(pg_result($resaco,$conresaco,'o50_programa'))."','$this->o50_programa',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o50_estrutdespesa"]) || $this->o50_estrutdespesa != "")
           $resac = db_query("insert into db_acount values($acount,759,5349,'".AddSlashes(pg_result($resaco,$conresaco,'o50_estrutdespesa'))."','$this->o50_estrutdespesa',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o50_estrutelemento"]) || $this->o50_estrutelemento != "")
           $resac = db_query("insert into db_acount values($acount,759,5351,'".AddSlashes(pg_result($resaco,$conresaco,'o50_estrutelemento'))."','$this->o50_estrutelemento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o50_estrutreceita"]) || $this->o50_estrutreceita != "")
           $resac = db_query("insert into db_acount values($acount,759,5350,'".AddSlashes(pg_result($resaco,$conresaco,'o50_estrutreceita'))."','$this->o50_estrutreceita',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o50_tipoproj"]) || $this->o50_tipoproj != "")
           $resac = db_query("insert into db_acount values($acount,759,9483,'".AddSlashes(pg_result($resaco,$conresaco,'o50_tipoproj'))."','$this->o50_tipoproj',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o50_utilizapacto"]) || $this->o50_utilizapacto != "")
           $resac = db_query("insert into db_acount values($acount,759,14058,'".AddSlashes(pg_result($resaco,$conresaco,'o50_utilizapacto'))."','$this->o50_utilizapacto',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o50_liberadecimalppa"]) || $this->o50_liberadecimalppa != "")
           $resac = db_query("insert into db_acount values($acount,759,15651,'".AddSlashes(pg_result($resaco,$conresaco,'o50_liberadecimalppa'))."','$this->o50_liberadecimalppa',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o50_estruturarecurso"]) || $this->o50_estruturarecurso != "")
           $resac = db_query("insert into db_acount values($acount,759,18119,'".AddSlashes(pg_result($resaco,$conresaco,'o50_estruturarecurso'))."','$this->o50_estruturarecurso',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o50_estruturacp"]) || $this->o50_estruturacp != "")
           $resac = db_query("insert into db_acount values($acount,759,18118,'".AddSlashes(pg_result($resaco,$conresaco,'o50_estruturacp'))."','$this->o50_estruturacp',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Par�metros do Or�amento nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->o50_anousu;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Par�metros do Or�amento nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->o50_anousu;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Altera��o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->o50_anousu;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($o50_anousu=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($o50_anousu));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,5345,'$o50_anousu','E')");
         $resac = db_query("insert into db_acount values($acount,759,5345,'','".AddSlashes(pg_result($resaco,$iresaco,'o50_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,759,5346,'','".AddSlashes(pg_result($resaco,$iresaco,'o50_coddot'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,759,5347,'','".AddSlashes(pg_result($resaco,$iresaco,'o50_subelem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,759,5348,'','".AddSlashes(pg_result($resaco,$iresaco,'o50_programa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,759,5349,'','".AddSlashes(pg_result($resaco,$iresaco,'o50_estrutdespesa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,759,5351,'','".AddSlashes(pg_result($resaco,$iresaco,'o50_estrutelemento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,759,5350,'','".AddSlashes(pg_result($resaco,$iresaco,'o50_estrutreceita'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,759,9483,'','".AddSlashes(pg_result($resaco,$iresaco,'o50_tipoproj'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,759,14058,'','".AddSlashes(pg_result($resaco,$iresaco,'o50_utilizapacto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,759,15651,'','".AddSlashes(pg_result($resaco,$iresaco,'o50_liberadecimalppa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,759,18119,'','".AddSlashes(pg_result($resaco,$iresaco,'o50_estruturarecurso'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,759,18118,'','".AddSlashes(pg_result($resaco,$iresaco,'o50_estruturacp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from orcparametro
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($o50_anousu != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " o50_anousu = $o50_anousu ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Par�metros do Or�amento nao Exclu�do. Exclus�o Abortada.\\n";
       $this->erro_sql .= "Valores : ".$o50_anousu;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Par�metros do Or�amento nao Encontrado. Exclus�o n�o Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$o50_anousu;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclus�o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$o50_anousu;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
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
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $this->numrows = pg_numrows($result);
      if($this->numrows==0){
        $this->erro_banco = "";
        $this->erro_sql   = "Record Vazio na Tabela:orcparametro";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $o50_anousu=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from orcparametro ";
     $sql .= "      inner join db_estrutura  on  db_estrutura.db77_codestrut = orcparametro.o50_estruturacp";
     $sql2 = "";
     if($dbwhere==""){
       if($o50_anousu!=null ){
         $sql2 .= " where orcparametro.o50_anousu = $o50_anousu "; 
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
   function sql_query_file ( $o50_anousu=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from orcparametro ";
     $sql2 = "";
     if($dbwhere==""){
       if($o50_anousu!=null ){
         $sql2 .= " where orcparametro.o50_anousu = $o50_anousu "; 
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